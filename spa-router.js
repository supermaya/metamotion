/**
 * SPA Router - Single Page Application 라우터
 * URL을 루트 도메인으로 유지하면서 콘텐츠를 동적으로 로드
 */

class SPARouter {
    constructor() {
        this.routes = {};
        this.currentPage = null;
        this.contentCache = {};
        this.init();
    }

    init() {
        // 페이지 로드 시 초기 라우트 처리
        window.addEventListener('load', () => {
            this.handleRoute(window.location.pathname);
        });

        // 브라우저 뒤로가기/앞으로가기 처리
        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.page) {
                this.loadPage(e.state.page, false);
            }
        });

        // 모든 링크 클릭 가로채기
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            if (link && this.isInternalLink(link)) {
                e.preventDefault();
                const href = link.getAttribute('href');
                this.navigate(href);
            }
        });
    }

    isInternalLink(link) {
        const href = link.getAttribute('href');
        // 외부 링크, mailto, tel, # 등은 제외
        if (!href || href.startsWith('http') || href.startsWith('mailto:') ||
            href.startsWith('tel:') || href.startsWith('#') ||
            href.startsWith('javascript:')) {
            return false;
        }
        return true;
    }

    navigate(path) {
        // 경로 정규화
        path = this.normalizePath(path);

        // History API로 URL 변경 (루트로 유지하되 state에 페이지 정보 저장)
        history.pushState({ page: path }, '', '/');

        // 페이지 로드
        this.loadPage(path, true);
    }

    normalizePath(path) {
        // 확장자 제거
        path = path.replace(/\.html$/, '');

        // 루트 경로 처리
        if (path === '' || path === '/' || path === 'index') {
            return 'index';
        }

        // 앞의 슬래시 제거
        return path.replace(/^\//, '');
    }

    handleRoute(pathname) {
        const path = this.normalizePath(pathname);
        this.loadPage(path, false);
    }

    async loadPage(pageName, updateHistory) {
        // 이미 현재 페이지면 리턴
        if (this.currentPage === pageName) {
            return;
        }

        // 페이지 파일명 결정
        let filename = pageName === 'index' ? 'index.html' : `${pageName}.html`;

        // 언어별 처리
        if (pageName.startsWith('index_')) {
            filename = `${pageName}.html`;
        } else if (pageName.startsWith('info')) {
            filename = `${pageName}.html`;
        }

        try {
            // 페이지 콘텐츠 로드 (캐시 확인)
            let content = this.contentCache[filename];

            if (!content) {
                const response = await fetch(filename);
                if (!response.ok) {
                    throw new Error(`Failed to load ${filename}`);
                }
                content = await response.text();
                this.contentCache[filename] = content;
            }

            // HTML 파싱
            const parser = new DOMParser();
            const doc = parser.parseFromString(content, 'text/html');

            // body 콘텐츠만 교체
            const newBody = doc.body;
            document.body.innerHTML = newBody.innerHTML;

            // 타이틀 업데이트
            const newTitle = doc.querySelector('title');
            if (newTitle) {
                document.title = newTitle.textContent;
            }

            // 스크립트 재실행 (페이지 내 스크립트)
            const scripts = document.body.querySelectorAll('script');
            scripts.forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.textContent = oldScript.textContent;
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });

            // 현재 페이지 업데이트
            this.currentPage = pageName;

            // 페이지 최상단으로 스크롤
            window.scrollTo(0, 0);

            // History 업데이트 (navigate에서 호출된 경우는 이미 처리됨)
            if (updateHistory) {
                history.replaceState({ page: pageName }, '', '/');
            }

        } catch (error) {
            console.error('Page load error:', error);

            // 에러 시 실제 페이지로 이동
            window.location.href = filename;
        }
    }
}

// 라우터 초기화
const router = new SPARouter();
