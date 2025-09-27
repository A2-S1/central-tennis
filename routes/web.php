<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TennisCourtController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RankingsController;
use App\Http\Controllers\TournamentsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\MatchesController;
use App\Http\Controllers\LocalTournamentsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Admin\StoreProductsController;
use App\Http\Controllers\Admin\StoreCategoriesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ClassifiedsController;
use App\Http\Controllers\Admin\ClassifiedsAdminController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('landing', ['hideNavbarLinks' => true, 'hideNavbarEntire' => true]);
});

// Admin
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::put('/users/{user}/admin', [AdminController::class, 'setUserAdmin'])->name('users.set_admin');
    Route::post('/users/{user}/delete', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/users/{user}/delete', [AdminController::class, 'deleteUser']);
    Route::get('/courts', [AdminController::class, 'courts'])->name('courts');
    Route::post('/courts/{court}/delete', [AdminController::class, 'deleteCourt'])->name('courts.delete');
    Route::get('/courts/{court}/delete', [AdminController::class, 'deleteCourt']);
    Route::get('/tournaments', [AdminController::class, 'tournaments'])->name('tournaments');
    Route::post('/tournaments/{local_tournament}/delete', [AdminController::class, 'deleteTournament'])->name('tournaments.delete');
    Route::get('/tournaments/{local_tournament}/delete', [AdminController::class, 'deleteTournament']);
    Route::get('/news', [AdminController::class, 'news'])->name('news');
    Route::post('/news/{news}/delete', [AdminController::class, 'deleteNews'])->name('news.delete');
    Route::get('/news/{news}/delete', [AdminController::class, 'deleteNews']);

    // Admin Loja
    Route::prefix('store')->name('store.')->group(function(){
        // Produtos
        Route::get('/products', [StoreProductsController::class, 'index'])->name('products.index');
        Route::get('/products/create', [StoreProductsController::class, 'create'])->name('products.create');
        Route::post('/products', [StoreProductsController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [StoreProductsController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [StoreProductsController::class, 'update'])->name('products.update');
        Route::post('/products/{product}/delete', [StoreProductsController::class, 'destroy'])->name('products.delete');

        // Categorias
        Route::get('/categories', [StoreCategoriesController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [StoreCategoriesController::class, 'create'])->name('categories.create');
        Route::post('/categories', [StoreCategoriesController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [StoreCategoriesController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [StoreCategoriesController::class, 'update'])->name('categories.update');
        Route::post('/categories/{category}/delete', [StoreCategoriesController::class, 'destroy'])->name('categories.delete');
    });

    // Admin Classificados
    Route::prefix('classifieds')->name('classifieds.')->group(function(){
        Route::get('/pending', [ClassifiedsAdminController::class, 'pending'])->name('pending');
        Route::get('/approved', [ClassifiedsAdminController::class, 'approved'])->name('approved');
        Route::post('/{listing}/approve', [ClassifiedsAdminController::class, 'approve'])->name('approve');
        Route::post('/{listing}/reject', [ClassifiedsAdminController::class, 'reject'])->name('reject');
        Route::post('/{listing}/delete', [ClassifiedsAdminController::class, 'destroy'])->name('delete');
    });
});

// Torneios Locais (ordem importa para não conflitar com {local_tournament})
Route::get('/local-tournaments', [LocalTournamentsController::class, 'index'])->name('local_tournaments.index');
Route::middleware('auth')->group(function () {
    Route::get('/local-tournaments/create', [LocalTournamentsController::class, 'create'])->name('local_tournaments.create');
    Route::post('/local-tournaments', [LocalTournamentsController::class, 'store'])->name('local_tournaments.store');
    Route::get('/local-tournaments/{local_tournament}/edit', [LocalTournamentsController::class, 'edit'])->name('local_tournaments.edit');
    Route::put('/local-tournaments/{local_tournament}', [LocalTournamentsController::class, 'update'])->name('local_tournaments.update');
    Route::delete('/local-tournaments/{local_tournament}', [LocalTournamentsController::class, 'destroy'])->name('local_tournaments.destroy');
});
Route::get('/local-tournaments/{local_tournament}', [LocalTournamentsController::class, 'show'])->name('local_tournaments.show');

// Loja - Público
Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/store/c/{slug}', [StoreController::class, 'category'])->name('store.category');
Route::get('/store/p/{slug}', [StoreController::class, 'product'])->name('store.product');

// Carrinho
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');

// Webhooks
Route::post('/webhooks/mercadopago', function(){ return response()->json(['ok'=>true]); });

// Classificados - Público
Route::get('/classifieds', [ClassifiedsController::class, 'index'])->name('classifieds.index');
// Rotas autenticadas devem vir ANTES do wildcard {listing} para evitar capturar 'create' como id
Route::middleware('auth')->group(function(){
    Route::get('/classifieds/create', [ClassifiedsController::class, 'create'])->name('classifieds.create');
    Route::post('/classifieds', [ClassifiedsController::class, 'store'])->name('classifieds.store');
    Route::get('/my-classifieds', [ClassifiedsController::class, 'my'])->name('classifieds.my');
    Route::get('/classifieds/{listing}/edit', [ClassifiedsController::class, 'edit'])->name('classifieds.edit');
    Route::put('/classifieds/{listing}', [ClassifiedsController::class, 'update'])->name('classifieds.update');
    Route::delete('/classifieds/{listing}/images/{imageId}', [ClassifiedsController::class, 'deleteImage'])->name('classifieds.images.delete');
    Route::post('/classifieds/{listing}/sold', [ClassifiedsController::class, 'markSold'])->name('classifieds.sold');
    Route::post('/classifieds/{listing}/delete', [ClassifiedsController::class, 'deleteOwn'])->name('classifieds.delete');
});
Route::get('/classifieds/{listing}', [ClassifiedsController::class, 'show'])->whereNumber('listing')->name('classifieds.show');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Alias para dashboard (alguns templates usam /dashboard)
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

// Páginas
Route::get('/quem-somos', [PageController::class, 'about'])->name('pages.about');

// Notícias
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::middleware('auth')->group(function(){
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
});

// Quadras (CRUD)
Route::resource('courts', TennisCourtController::class);

// Comunidade (busca de parceiros)
Route::get('/community', [CommunityController::class, 'index'])->name('community.index');

// Rankings ATP/WTA
Route::get('/rankings', [RankingsController::class, 'index'])->name('rankings.index');

// Torneios
Route::get('/tournaments', [TournamentsController::class, 'index'])->name('tournaments.index');

// Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Perfil público de jogador via slug
Route::get('/players/{user:slug}', [PlayerController::class, 'show'])->name('players.show');

// Fallback por ID (redireciona para slug correto)
Route::get('/players/id/{user}', function (\App\Models\User $user) {
    if (!$user->public_profile) abort(404);
    return redirect()->route('players.show', $user);
})->name('players.by_id');

// Jogos (matches)
Route::middleware('auth')->group(function () {
    Route::get('/matches', [MatchesController::class, 'index'])->name('matches.index');
    Route::get('/matches/invite', [MatchesController::class, 'invite'])->name('matches.invite');
    Route::post('/matches', [MatchesController::class, 'store'])->name('matches.store');
    Route::post('/matches/{match}/accept', [MatchesController::class, 'accept'])->name('matches.accept');
    Route::post('/matches/{match}/reject', [MatchesController::class, 'reject'])->name('matches.reject');
    Route::post('/matches/{match}/cancel', [MatchesController::class, 'cancel'])->name('matches.cancel');
    Route::get('/matches/{match}/result', [MatchesController::class, 'resultForm'])->name('matches.result.form');
    Route::post('/matches/{match}/result', [MatchesController::class, 'resultSubmit'])->name('matches.result.submit');
    Route::get('/matches/history', [MatchesController::class, 'history'])->name('matches.history');
});
