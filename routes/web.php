<?php


//---------- Home Page ----------  
Route::get('/','controlpage@index');
Route::get('/index','controlpage@index');

//---------- register route ----------  
Route::post('/register1','controlpage@registerstore');
Route::get('/register','controlpage@register');

//---------- login route ----------  
Route::post('/login1','controlpage@loginstore');
Route::get('/login','controlpage@login');

//---------- Logout ----------
Route::get('/logout','controlpage@logout');

//---------- display Autism child ----------
Route::get('/display/{sp_id}','controlpage@display');

//---------- display Modify account specialist information view ----------
Route::get('/edit/{sp_id}','controlpage@edit');

//---------- Modify account specialist information ----------
Route::post('/saveedit','controlpage@saveedit');

//---------- Display Stages For Child  ----------
Route::get('/stage/{ch_id}','controlpage@stage');

//---------- Display Modify Child Stage view ----------
Route::get('/updatestage/{ch_id}/{st_id}','controlpage@updatestage');

//---------- Modify child Stage  ----------`  
Route::post('/SaveUpdateSt','controlpage@SaveUpdateSt');

//---------- Display Child Stage Page For Add Note  ----------
Route::get('/noteSt/{ch_id}','controlpage@noteSt');

//----------  Add Note For Child Stage  ----------
Route::get('/addnoteSt/{ch_id}/{st_id}','controlpage@addnoteSt');

//----------  Save Note Added For Child Stage  ----------
Route::post('/savenotee','controlpage@savenotee');

//----------  Display Child Details  View  ----------
Route::get('/displaychild/{ch_id}','controlpage@displaychild');

//---------- Return To  Display Autism Child  View  ----------
Route::post('/homechild','controlpage@homechild');

//---------- delete child ----------
Route::get('/delete/{ch_id}','controlpage@delete');

//---------- display chat view ----------
Route::get('/chat/{sp_id}','controlpage@chat');

Auth::routes();

//---------- Home View Authentication ----------
Route::get('/home', 'HomeController@index')->name('home');

//---------- Search Engine ----------

//---------- Search Page To Sarch for a Query ----------
Route::get('Searchh','newController@Searchh');

//---------- function for matching between Query word and file word    
//using for matching Vector Space Model
//----------
Route::post('addQuery','newController@addQuery');

/***
//---------- Insert To DB After make Indexing (1.make Tokenization and remove stop word and 
                                             //2.make stemming using algorithm porter2 and
                                             //3.make lemmatization using Skyeng\Lemmatizer library and 
                                             //4.calculate word frequancy by "N gram" Algorithm   ) 
//It Is Called Once To Insert Documents To DB
// ----------
**/
Route::get('insert_all','newController@insert_all');

//---------- Term Weighting by calculate Tfidf for word   
//It Is Called Once To Caculate Tfidf for word and insert it  To DB
//----------
Route::get('tfidf','newController@tfidf');

//
Route::get('ss/{file_id}','newController@ss');