<?php

/*
    home的路由
*/
// 商品查询
Route::get('/','Home\ChaXunController@index')->name('ChaXunIndex');
Route::get('/chaxuninfo','Home\ChaXunController@result')->name('chaxuninfo');

//　商品的栏目列表
Route::get('goods/{categoryId}', 'Home\CategoryController@index')->where(['categoryId'=>'[0-9]+'])->name('homeGoodsListCategory');

// 商品详情跳转确认
Route::get('coupon/{id}','Home\UrlConfirmController@couponIndex')->name('urlCouponInfo');                      // 优惠券的商品信息跳转
Route::get('favoritesitem/{id}','Home\UrlConfirmController@favoritesItemIndex')->name('urlFavoritesItemInfo'); // 选品库的商品信息跳转

// 生成二维码图片的网页
Route::get('img/{form}/{id}', 'Home\MakeImagesController@index')->where(['id'=>'[0-9]+']);    // 生成二维码的链接

/*
    admin的路由
*/
// 系统自带的认证路由...
Route::get('auth/login', 'Auth\AuthController@getLogin')->name('login');
Route::post('auth/login', 'Auth\AuthController@postLogin')->name('loginaction');
Route::get('auth/logout', 'Auth\AuthController@getLogout')->name('logout');

// 系统自带的注册路由...
Route::get('auth/register', 'Auth\AuthController@getRegister')->name('register');
// Route::post('auth/register', 'Auth\AuthController@postRegister')->name('registeraction');

// 系统自带的密码重置邮件发送链接的路由...
Route::get('password/email', 'Auth\PasswordController@getEmail')->name('pwdemail');
// Route::post('password/email', 'Auth\PasswordController@postEmail')->name('pwdemailaction');

// 系统自带的密码重置的路由...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset')->name('pwdreset');
// Route::post('password/reset', 'Auth\PasswordController@postReset')->name('pwdresetaction');

// 只有用户登录了账户才能访问的路由
Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
	// 后台的控制面板
	Route::get('dashboard','Admin\DashboardController@index')->name('dashboard');
	
	// 修改用户的个人信息
	Route::get('user/changepwd','Auth\UserController@changePassword')->name('changepwd');
	Route::post('user/changepwdaction','Auth\UserController@changePasswordAction')->name('changepwdaction');

	// 商品分析
	Route::get('coupon/analysis','Admin\CouponController@analysisIndex')->name('couponAnalysisIndex'); 	// 优惠券商品分析

	// 网站栏目管理
	Route::get('category/index', 'Admin\CategoryController@index')->name('adminCategoryIndex');                 // 显示栏目列表
	Route::get('category/add', 'Admin\CategoryController@add')->name('adminCategoryAdd');                       // 显示增加栏目的页面
	Route::post('category/addaction', 'Admin\CategoryController@addAction')->name('adminCategoryAddAction');    // 执行增加栏目的操作
	Route::get('category/update', 'Admin\CategoryController@update')->name('adminCategoryUpdate');              // 显示修改栏目的页面
	Route::post('category/updateaction', 'Admin\CategoryController@updateAction')->name('adminCategoryUpdateAction');             // 执行修改栏目的操作
	Route::post('category/updateorderselfs', 'Admin\CategoryController@updateOrderselfs')->name('adminCategoryUpdateOrderselfs'); // 根据ID批量修改栏目排序的操作
	Route::get('category/delbyid', 'Admin\CategoryController@deleteById')->name('adminCategoryDeleteById');     // 根据id删除栏目
	Route::post('category/delbyids', 'Admin\CategoryController@deleteByIds')->name('adminCategoryDeleteByIds'); // 根据id删除栏目

	// 优惠券商品相关
	Route::get('coupon/list','Admin\CouponController@index')->name('couponlist'); 	// 显示优惠券的列表信息
	Route::get('coupon/list/del','Admin\CouponController@delete')->name('couponDeleteOne');  			// 删除特定的优惠券(单个)
	Route::post('coupon/list/delmany','Admin\CouponController@deleteMany')->name('couponDeleteMany'); 	// 删除多个优惠券
	Route::get('coupon/list/deldate','Admin\CouponController@deleteDate')->name('couponDeleteDate'); 	// 按日期删除优惠券
	Route::get('coupon/list/delAll','Admin\CouponController@deleteAll')->name('couponDeleteAll'); 		// 删除所有优惠券
	Route::get('coupon/list/recommendone','Admin\CouponController@recommendOne')->name('couponRecommendOne'); 		// 推荐单个优惠券商品
	Route::post('coupon/list/recommendmany','Admin\CouponController@recommendMany')->name('couponRecommendMany'); 	// 推荐多个优惠券商品
	Route::get('coupon/list/cancelrecommendone','Admin\CouponController@cancelRecommendOne')->name('couponCancelRecommendOne'); 	// 取消推荐单个优惠券
	Route::post('coupon/list/cancelrecommendmany','Admin\CouponController@cancelRecommendMany')->name('couponCancelRecommendMany'); // 取消推荐多个优惠券

	// 上传Excel文件到数据库
	Route::get('coupon/list/excelupload','Admin\CouponController@excelupload')->name('listexcelupload'); 	                  // 显示上传Excel文件的窗口
	Route::post('coupon/list/exceluploadaction','Admin\CouponController@exceluploadaction')->name('couponExceluploadaction'); // 处理上传的Excel文件

	// 优惠券的分类目录
	Route::get('coupon/category/list', 'Admin\CouponCategoryController@index')->name('couponCategoryList'); // 显示优惠券分类的列表
	Route::get('coupon/category/add', 'Admin\CouponCategoryController@add')->name('couponCategoryAdd'); 	// 显示增加优惠券分类的页面
	Route::get('coupon/category/edit', 'Admin\CouponCategoryController@edit')->name('couponCategoryEdit'); 	// 显示编辑优惠券分类的页面
	Route::post('coupon/category/addaction', 'Admin\CouponCategoryController@addaction')->name('couponCategoryAddaction');       // 向数据库写入增加分类的信息
	Route::get('coupon/category/del', 'Admin\CouponCategoryController@del')->name('couponCategoryDel');                          // 删除单个优惠券信息
	Route::post('coupon/category/delmany', 'Admin\CouponCategoryController@delMany')->name('couponCategoryDelMany');             // 批量删除优惠券信息
	Route::get('coupon/category/isshow', 'Admin\CouponCategoryController@isShow')->name('couponCategoryIsShow');                 // 删除单个优惠券信息
	Route::post('coupon/category/changeOrder', 'Admin\CouponCategoryController@changeOrder')->name('couponCategoryChangeOrder'); // 批量修改分类的order值

	// 选品库列表
		// 面板
	Route::get('favorites/dashboard', 'Admin\FavoritesCategoryController@dashboard')->name('favoritesDashboard');             // 显示选品库入库的面板
	Route::get('favorites/dashboardlist', 'Admin\FavoritesCategoryController@dashboardList')->name('favoritesDashboardList'); // 在控制面板显示选品库列表
	Route::get('favorites/list', 'Admin\FavoritesCategoryController@fList')->name('favoritesList'); 						   // 根据栏目显示选品库列表
	Route::get('favorites/99', 'Admin\FavoritesCategoryController@jiuKuaiJiuIndex')->name('favoritesJiuKuaiJiu');             // 选品库列表之9.9元包邮
	Route::get('favorites/20', 'Admin\FavoritesCategoryController@ErShiIndex')->name('favoritesErShi');                       // 选品库列表之20元封顶
		// 处理选品库列表的相关操作
	Route::get('favorites/insertAll', 'Admin\FavoritesCategoryController@insertAll')->name('favoritesInsertAll');          // 将选品库列表全部入库
	Route::get('favorites/delAll', 'Admin\FavoritesCategoryController@deltAll')->name('favoritesDeleteAll'); 		        // 将选品库列表的数据全部删除
	Route::get('favorites/delManyDate', 'Admin\FavoritesCategoryController@deletetDate')->name('favoritesDeleteManyDate'); // 将选品库列表的特定日期的数据全部删除
	Route::get('favorites/delid', 'Admin\FavoritesCategoryController@deleteId')->name('favoritesDeleteId');                // 将选品库列表根据id来进行删除
	Route::post('favorites/delids', 'Admin\FavoritesCategoryController@deleteIds')->name('favoritesDeleteIds');            // 将选品库列表根据id的集合来进行删除
	Route::get('favorites/editid', 'Admin\FavoritesCategoryController@editId')->name('favoritesEditId');                   // 将选品库列表根据id来进行编辑
	Route::post('favorites/update', 'Admin\FavoritesCategoryController@update')->name('favoritesUpdate');                  // 将批量更新选品库列表
	Route::post('favorites/updatebyid', 'Admin\FavoritesCategoryController@updateById')->name('favoritesUpdateById');      // 根据id修改选品库列表
	Route::post('favorites/updateorder', 'Admin\FavoritesCategoryController@updateOrder')->name('favoritesUpdateOder');    // 根据id修改选品库列表

	// 选品库的宝贝信息
		// 面板
	Route::get('favoritesitem/dashboard', 'Admin\FavoritesItemController@dashboardIndex')->name('favoritesItemDashboard'); // 显示选品库宝贝入库的面板
	Route::get('favoritesitem/goodsList', 'Admin\FavoritesItemController@goodsList')->name('favoritesItemGoodsList');      // 显示选品库宝贝信息的面板
		// 处理选品库宝贝的相关操作
	Route::get('favoritesitem/update/favoritesidsall', 'Admin\FavoritesItemController@updateByFavoritesIdsAll')->name('favoritesItemUpdateByFavoritesIdsAll');     // 根据选品库列表的所有id来更新选品库宝贝的信息
	Route::post('favoritesitem/update/favoritesidssome', 'Admin\FavoritesItemController@updateByFavoritesIdsSome')->name('favoritesItemUpdateByFavoritesIdsSome'); // 根据选品库列表的id集合来更新选品库宝贝的信息
	Route::get('favoritesitem/update/favoritesid', 'Admin\FavoritesItemController@updateByFavoritesId')->name('favoritesItemUpdateByFavoritesId'); 				// 根据选品库列表的单个ID来更新选品库宝贝的信息
	Route::get('favoritesitem/delete/all', 'Admin\FavoritesItemController@deleteAll')->name('favoritesItemDeleteAll');     			   					            // 删除所有的选品库宝贝的信息
	Route::get('favoritesitem/delete/fromdate', 'Admin\FavoritesItemController@deleteFromDate')->name('favoritesItemDeleteFromDate');     					            // 删除特定日期的所有选品库的宝贝信息
	Route::post('favoritesitem/delete/favoritesids', 'Admin\FavoritesItemController@deleteByFavoritesIds')->name('favoritesItemDeleteByFavoritesIds');                 // 根据favorites_id的集合删除商品信息
	Route::get('favoritesitem/delete/favoritesid', 'Admin\FavoritesItemController@deleteByFavoritesId')->name('favoritesItemDeleteByFavoritesId');     	            // 根据favorites_id删除商品信息
	Route::post('favoritesitem/delete/statusoff', 'Admin\FavoritesItemController@deleteStatusOff')->name('favoritesItemDeleteStatusOff');     							// 根据favorites_id集合删除无效的商品信息
	Route::get('favoritesitem/delete/statusofffavoriteid', 'Admin\FavoritesItemController@deleteStatusOffFavoriteId')->name('favoritesItemDeleteStatusOffFavoriteId'); // 根据favorites_id删除无效的商品信息
	Route::post('favoritesitem/delete/updateNoToday', 'Admin\FavoritesItemController@deleteUpdateNoToday')->name('favoritesItemDeleteUpdateNoToday');     				// 根据favorites_id集合删除选中今日无更新的宝贝信息
	Route::get('favoritesitem/delete/updateNoTodaybyfid', 'Admin\FavoritesItemController@deleteUpdateNoTodayByFid')->name('favoritesItemDeleteUpdateNoTodayByFid');    // 根据favorites_id删除选中今日无更新的宝贝信息
	Route::get('favoritesitem/delete/itemId', 'Admin\FavoritesItemController@deleteItemById')->name('favoritesItemDeleteByItemId');                           // 根据id删除选品库商品的信息
	Route::post('favoritesitem/delete/itemIds', 'Admin\FavoritesItemController@deleteItemByIds')->name('favoritesItemDeleteByItemIds');                       // 根据id的集合删除选品库商品的信息
	Route::get('favoritesitem/recommenditem/id', 'Admin\FavoritesItemController@recommendItemById')->name('favoritesItemRecommendItemByItemId');              // 根据id推荐选品库商品的信息
	Route::post('favoritesitem/recommenditem/ids', 'Admin\FavoritesItemController@recommendItemByIds')->name('favoritesItemRecommendItemByItemIds');          // 根据ids推荐选品库商品的信息
	Route::get('favoritesitem/notrecommenditem/id', 'Admin\FavoritesItemController@notRecommendItemById')->name('favoritesItemNotRecommendItemByItemId');     // 根据id取消推荐选品库商品的信息
	Route::post('favoritesitem/notrecommenditem/ids', 'Admin\FavoritesItemController@notRecommendItemByIds')->name('favoritesItemNotRecommendItemByItemIds'); // 根据ids取消推荐选品库商品的信息

	// 选品库商品的入库
	Route::get('xuanpinkuinsertcate','Admin\XuanPinKuController@insertCate');   //插入选品库的分类
	Route::get('xuanpinkuinsertgoods','Admin\XuanPinKuController@insertGoods'); //插入选品库的商品

	// 上传优惠券的Excel表格（旧）
	Route::get('excelupload','Admin\ExcelController@create')->name('excelupload');
	Route::post('exceluploadaction','Admin\ExcelController@store');
});



// Route::get('taobao/test','Taobao\TestController@index'); // 测试淘宝api用，可删除









// 待开发的功能
// // 自己定义的认证路由...
// Route::get('admin/login', 'Login\LoginController@getLogin');
// Route::post('admin/login', 'Login\LoginController@postLogin');
// Route::get('admin/logout', 'Login\LoginController@getLogout');

// 自己定义的注册路由...
// Route::get('admin/register', 'Login\LoginController@getRegister');
// Route::post('admin/register', 'Login\LoginController@postRegister');