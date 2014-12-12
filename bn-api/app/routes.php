<?php
Core\Route::get('test', 'test');
Core\Route::post('register', 'registerUser');
Core\Route::post('friends', 'setFriends');
Core\Route::get('friends', 'getFriends');
Core\Route::put('group', 'updateGroup');