<?php
$pages = [
  'admin' => [
    'name' => 'Admin',
    'link' => 'admin/',
    'icon' => 'feather/user.svg'
  ],
  'logout' => [
    'name' => 'Logout',
    'link' => 'admin/logout.php',
    'icon' => 'feather/log-out.svg'
  ]
];
$adminmenu = [
  'category' => [
    'name' => 'Category',
    'link' => 'admin/category.php',
    'icon' => 'feather/paperclip.svg'
  ],
  'write' => [
    'name' => 'Write',
    // 'link' => 'admin/write.php',
    'link' => 'index.php?article=new',
    'icon' => 'feather/feather.svg'
  ],
  'archive' => [
    'name' => 'Archive',
    'link' => 'admin/archive.php',
    'icon' => 'feather/archive.svg'
  ],
  //'comments' => [
    //'name' => 'Comments',
    //'link' => 'admin/#',
    //'icon' => 'feather/message-square.svg'
  //],
  'images' => [
    'name' => 'Images',
    'link' => 'admin/images.php',
    'icon' => 'feather/image.svg'
  ],
  'settings' => [
    'name' => 'Settings',
    'link' => 'admin/settings.php',
    'icon' => 'feather/sliders.svg'
  ]
];
?>
