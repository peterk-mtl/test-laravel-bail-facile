<?php

use Illuminate\Support\Facades\Route;
use App\Models\Document;
use Illuminate\Support\Facades\App;
use Faker\Generator;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/documents/{document}', function (Document $document) {
    $fakerGenerator = App::make(Faker\Generator::class);

    $templatePath = 'documentTypesTemplates.' . $document->documentType->slug;
    $cacheKey = $templatePath . '.' . $document->id;

    $html = Cache::remember($cacheKey, 300, function () use ($fakerGenerator) {
        return $fakerGenerator->randomHtml();
    });

    return view($templatePath, ['html' => $html]);
})->name('documents.template');
