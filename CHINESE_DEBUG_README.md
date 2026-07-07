# Chinese Language Debugging Tools

## 🚨 Problem
Chinese main page (index_cn.html) displays Korean text instead of Chinese text in slides

## 🛠️ Available Debugging Tools

### 🎯 PRIMARY TOOL: `diagnose_chinese.html` (RECOMMENDED)
**Purpose**: Comprehensive browser-based diagnostic dashboard

**How to Use**:
1. Open in browser: `http://localhost/metamotion/diagnose_chinese.html`
2. Click "Run All Tests" button
3. Review results

**What It Tests**:
- ✅ API responses for all languages (ko, en, cn)
- ✅ Side-by-side language data comparison
- ✅ Browser cache status
- ✅ Current page verification
- ✅ Automatic cache clearing

**Interpretation**:
- **If Chinese data is empty** → Database doesn't have Chinese data stored
- **If Chinese data exists** → Cache issue or viewing wrong page
- **Color coding**: Korean (red), English (blue), Chinese (yellow)

---

### 🔍 TOOL 2: `direct_db_test.php`
**Purpose**: Direct database query execution via PHP

**How to Use**:
```
http://localhost/metamotion/direct_db_test.php
```

**Shows**:
1. Chinese column existence (title_cn, description_cn, image_url_cn)
2. Actual Chinese data stored in database
3. API query simulation
4. JSON output format

---

### 🔬 TOOL 3: `debug_cn.html`
**Purpose**: Individual API endpoint testing

**How to Use**:
```
http://localhost/metamotion/debug_cn.html
```

**Features**:
- Test Hero Slides API
- Test Solution Slides API
- Test Sections API
- Clear cache button

---

### 📊 TOOL 4: `test_api_cn.php`
**Purpose**: Server-side data verification

**How to Use**:
```
http://localhost/metamotion/test_api_cn.php
```

**Displays**:
- All language fields in tables
- API response simulation
- Column structure details

---

## 🔧 Troubleshooting Steps

### STEP 1: Run Diagnosis
1. Open `diagnose_chinese.html`
2. Click "전체 테스트 실행" (Run All Tests)
3. Wait for results

### STEP 2: Identify Problem Type

#### Scenario A: Chinese Data is Empty
**Symptoms**:
- API response shows NULL or empty for Chinese title/description
- Table shows "(비어있음)" or "(empty)" for Chinese columns

**Solution**:
1. Go to admin page: `admin.html`
2. Login
3. Scroll to Hero Slides → Chinese (中国语) section
4. Enter Chinese text in title and description fields
5. Click "Save Hero Slides"
6. Repeat for Solution Slides and Section Images
7. Refresh `index_cn.html`

#### Scenario B: Chinese Data Exists But Not Displaying
**Symptoms**:
- API returns Chinese data correctly
- Page still shows Korean text

**Solution**:
1. Click "브라우저 캐시 클리어" (Clear Browser Cache) in `diagnose_chinese.html`
2. Press Ctrl+F5 for hard refresh
3. Open browser DevTools (F12)
4. Check Network tab → Look for `api.php` requests
5. Verify `lang=cn` parameter exists
6. Verify response contains Chinese data

#### Scenario C: Viewing Wrong Page
**Symptoms**:
- `diagnose_chinese.html` shows "잘못된 페이지" (Wrong Page) warning

**Solution**:
- Navigate to `index_cn.html` (not `index.html` or `index_en.html`)

---

## 🐛 Common Issues

### Issue 1: Admin Page Not Saving Chinese Data
**Check**:
- Logged in status
- "성공적으로 저장되었습니다" (Successfully saved) message appears
- Data persists after page refresh

**Debug**:
- Open browser console (F12) in admin.html
- Check for JavaScript errors
- Verify with `direct_db_test.php`

### Issue 2: API Returns Korean Instead of Chinese
**Check**:
- Line 762 in `index_cn.html`
- Should be: `api.php?type=hero&lang=cn`

**Fix**:
```javascript
// ✅ CORRECT
const heroResponse = await fetchWithTimeout('api.php?type=hero&lang=cn');

// ❌ WRONG (would return Korean data)
const heroResponse = await fetchWithTimeout('api.php?type=hero&lang=ko');
```

### Issue 3: Database Missing Chinese Columns
**Check**:
- Run `direct_db_test.php`
- Look for title_cn, description_cn, image_url_cn in "Table Structure"

**Fix**:
1. Open phpMyAdmin
2. Go to SQL tab
3. Copy contents of `add_chinese_fields.sql`
4. Execute SQL

---

## 📋 File Reference

| File | Purpose | Type |
|------|---------|------|
| `diagnose_chinese.html` | Main diagnostic tool | HTML (Browser) |
| `direct_db_test.php` | Database query test | PHP (Server) |
| `debug_cn.html` | API endpoint test | HTML (Browser) |
| `test_api_cn.php` | Server-side verification | PHP (Server) |
| `add_chinese_fields.sql` | Database schema update | SQL |
| `index_cn.html` | Chinese main page | HTML |
| `admin.html` | Admin panel | HTML |
| `api.php` | API endpoints | PHP |

---

## ✅ Success Checklist

Problem is solved when:

- [ ] `diagnose_chinese.html` shows Chinese data in all API responses
- [ ] `index_cn.html` Hero Slides display Chinese text
- [ ] Solution images show Chinese text
- [ ] Section images show Chinese text
- [ ] Chinese text persists after page refresh
- [ ] No Korean text visible on Chinese page

---

## 🆘 Getting Help

If issue persists after following this guide:

1. Run `diagnose_chinese.html` → Take screenshot
2. Run `direct_db_test.php` → Take screenshot
3. Open browser DevTools (F12) → Console tab → Copy errors
4. Provide all three screenshots/logs

---

**Created**: 2026-02-11
**Author**: Claude Code
**Version**: 1.0
