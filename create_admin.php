<?php
  require_once 'config.php';

  $email = 'admin@metamotion.io';
  $password = 'admin1234';  // 원하는 비밀번호로 변경

  try {
      $pdo = getDBConnection();
      $password_hash = password_hash($password,
  PASSWORD_DEFAULT);

      $stmt = $pdo->prepare("INSERT INTO admin_users (email,        
  password_hash) VALUES (?, ?)");
      $stmt->execute([$email, $password_hash]);

      echo "관리자 계정이 생성되었습니다.";
  } catch (Exception $e) {
      echo "오류: " . $e->getMessage();
  }
  ?>