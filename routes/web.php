<?php

use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CateringController as AdminCateringController;
use App\Http\Controllers\Admin\CateringPackageController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\GiftAmountController;
use App\Http\Controllers\Admin\GiftCardController as AdminGiftCardController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ToastController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Customer\AccountAuthController;
use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\AboutController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CateringController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\ContactController;
use App\Http\Controllers\Customer\GalleryController;
use App\Http\Controllers\Customer\GiftCardController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\HubController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\NewsletterController as CustomerNewsletterController;
use App\Http\Controllers\Customer\PromoController;
use App\Http\Controllers\Customer\ReservationController;
use App\Http\Controllers\Customer\UnsubscribeController;
use Illuminate\Support\Facades\Route;

Route::get('/hub', [HubController::class, 'index'])->name('hub');
Route::view('/offline', 'errors.offline')->name('offline');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/confirmed', [CheckoutController::class, 'confirmed'])->name('checkout.confirmed');

Route::get('/reserve', [ReservationController::class, 'create'])->name('reserve');
Route::post('/reserve', [ReservationController::class, 'store'])->name('reserve.store');

Route::get('/catering', [CateringController::class, 'create'])->name('catering');
Route::post('/catering/order', [CateringController::class, 'orderPerPerson'])->name('catering.order');
Route::post('/catering/trays/{slug}', [CateringController::class, 'addTray'])->name('catering.trays.add');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/offers', [PromoController::class, 'index'])->name('promos');
Route::post('/offers/{promo}/order', [PromoController::class, 'order'])->name('promos.order');
Route::post('/newsletter', [CustomerNewsletterController::class, 'store'])->name('newsletter.store');
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/gift-cards', [GiftCardController::class, 'create'])->name('giftcards');
Route::post('/gift-cards', [GiftCardController::class, 'store'])->name('giftcards.store');
Route::post('/gift-cards/balance', [GiftCardController::class, 'balance'])->name('giftcards.balance');

Route::get('/unsubscribe', [UnsubscribeController::class, 'index'])->name('unsubscribe');
Route::post('/unsubscribe/lookup', [UnsubscribeController::class, 'lookup'])->name('unsubscribe.lookup');
Route::get('/unsubscribe/{token}/email', [UnsubscribeController::class, 'email'])->name('unsubscribe.email');
Route::post('/unsubscribe/{token}/one-click', [UnsubscribeController::class, 'oneClick'])->name('unsubscribe.one-click');
Route::get('/unsubscribe/{token}', [UnsubscribeController::class, 'show'])->name('unsubscribe.show');
Route::post('/unsubscribe', [UnsubscribeController::class, 'store'])->name('unsubscribe.store');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{itemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{itemId}', [CartController::class, 'remove'])->name('cart.remove');

Route::prefix('account')->name('account.')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [AccountAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AccountAuthController::class, 'login']);
        Route::get('/register', [AccountAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AccountAuthController::class, 'register']);
        Route::get('/forgot-password', [AccountAuthController::class, 'showForgotForm'])->name('password.forgot');
        Route::post('/forgot-password', [AccountAuthController::class, 'sendForgotCode'])->name('password.forgot.send');
        Route::get('/reset-password', [AccountAuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [AccountAuthController::class, 'resetPassword'])->name('password.reset.store');
    });

    Route::post('/logout', [AccountAuthController::class, 'logout'])
        ->middleware('auth:customer')
        ->name('logout');

    Route::middleware('auth:customer')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/orders/{orderNumber}', [AccountController::class, 'showOrder'])->name('orders.show');
        Route::post('/reviews', [AccountController::class, 'storeReview'])->name('reviews.store');
        Route::patch('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::patch('/password', [AccountController::class, 'updatePassword'])->name('password.change');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::post('/reservations', [AdminReservationController::class, 'store'])->name('reservations.store');
        Route::patch('/reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.status');

        Route::get('/menu', [AdminMenuController::class, 'index'])->name('menu.index');
        Route::get('/menu/create', [AdminMenuController::class, 'create'])->name('menu.create');
        Route::post('/menu', [AdminMenuController::class, 'store'])->name('menu.store');
        Route::post('/menu/categories', [AdminMenuController::class, 'storeCategory'])->name('menu.categories.store');
        Route::patch('/menu/categories/{menuCategory}', [AdminMenuController::class, 'updateCategory'])->name('menu.categories.update');
        Route::delete('/menu/categories/{menuCategory}', [AdminMenuController::class, 'destroyCategory'])->name('menu.categories.destroy');
        Route::get('/menu/{menuItem}/edit', [AdminMenuController::class, 'edit'])->name('menu.edit');
        Route::put('/menu/{menuItem}', [AdminMenuController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{menuItem}', [AdminMenuController::class, 'destroy'])->name('menu.destroy');
        Route::patch('/menu/{menuItem}/availability', [AdminMenuController::class, 'toggleAvailability'])->name('menu.availability');

        Route::get('/catering', [AdminCateringController::class, 'index'])->name('catering.index');
        Route::patch('/catering/{catering}/status', [AdminCateringController::class, 'updateStatus'])->name('catering.status');
        Route::patch('/catering/{catering}/quote', [AdminCateringController::class, 'updateQuote'])->name('catering.quote');
        Route::post('/catering/packages', [CateringPackageController::class, 'store'])->name('catering.packages.store');
        Route::put('/catering/packages/{cateringPackage}', [CateringPackageController::class, 'update'])->name('catering.packages.update');
        Route::delete('/catering/packages/{cateringPackage}', [CateringPackageController::class, 'destroy'])->name('catering.packages.destroy');

        Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
        Route::post('/inquiries/{inquiry}/reply', [InquiryController::class, 'reply'])->name('inquiries.reply');
        Route::patch('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('inquiries.status');

        Route::get('/content', [ContentController::class, 'index'])->name('content.index');
        Route::patch('/content/{content}', [ContentController::class, 'update'])->name('content.update');

        Route::get('/about', [AdminAboutController::class, 'index'])->name('about.index');
        Route::put('/about/hero', [AdminAboutController::class, 'updateHero'])->name('about.hero.update');
        Route::post('/about/story', [AdminAboutController::class, 'storeStory'])->name('about.story.store');
        Route::put('/about/story/{story}', [AdminAboutController::class, 'updateStory'])->name('about.story.update');
        Route::delete('/about/story/{story}', [AdminAboutController::class, 'destroyStory'])->name('about.story.destroy');
        Route::post('/about/stats', [AdminAboutController::class, 'storeStat'])->name('about.stats.store');
        Route::put('/about/stats/{stat}', [AdminAboutController::class, 'updateStat'])->name('about.stats.update');
        Route::delete('/about/stats/{stat}', [AdminAboutController::class, 'destroyStat'])->name('about.stats.destroy');
        Route::post('/about/values', [AdminAboutController::class, 'storeValue'])->name('about.values.store');
        Route::put('/about/values/{value}', [AdminAboutController::class, 'updateValue'])->name('about.values.update');
        Route::delete('/about/values/{value}', [AdminAboutController::class, 'destroyValue'])->name('about.values.destroy');
        Route::post('/about/team', [AdminAboutController::class, 'storeTeam'])->name('about.team.store');
        Route::put('/about/team/{member}', [AdminAboutController::class, 'updateTeam'])->name('about.team.update');
        Route::delete('/about/team/{member}', [AdminAboutController::class, 'destroyTeam'])->name('about.team.destroy');

        Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('gallery.index');
        Route::post('/gallery', [AdminGalleryController::class, 'store'])->name('gallery.store');
        Route::put('/gallery/{galleryImage}', [AdminGalleryController::class, 'update'])->name('gallery.update');
        Route::delete('/gallery/{galleryImage}', [AdminGalleryController::class, 'destroy'])->name('gallery.destroy');

        Route::get('/promos', [AdminPromoController::class, 'index'])->name('promos.index');
        Route::post('/promos', [AdminPromoController::class, 'store'])->name('promos.store');
        Route::put('/promos/{promo}', [AdminPromoController::class, 'update'])->name('promos.update');
        Route::delete('/promos/{promo}', [AdminPromoController::class, 'destroy'])->name('promos.destroy');

        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('/gift-cards', [AdminGiftCardController::class, 'index'])->name('gift-cards.index');
        Route::post('/gift-cards', [AdminGiftCardController::class, 'store'])->name('gift-cards.store');
        Route::patch('/gift-cards/{giftCard}', [AdminGiftCardController::class, 'update'])->name('gift-cards.update');
        Route::post('/gift-cards/designs', [AdminGiftCardController::class, 'storeDesign'])->name('gift-cards.designs.store');
        Route::patch('/gift-cards/designs/{design}', [AdminGiftCardController::class, 'updateDesign'])->name('gift-cards.designs.update');
        Route::delete('/gift-cards/designs/{design}', [AdminGiftCardController::class, 'destroyDesign'])->name('gift-cards.designs.destroy');

        Route::get('/gift-amounts', [GiftAmountController::class, 'index'])->name('gift-amounts.index');
        Route::post('/gift-amounts', [GiftAmountController::class, 'store'])->name('gift-amounts.store');
        Route::put('/gift-amounts/{giftAmount}', [GiftAmountController::class, 'update'])->name('gift-amounts.update');
        Route::delete('/gift-amounts/{giftAmount}', [GiftAmountController::class, 'destroy'])->name('gift-amounts.destroy');

        Route::get('/newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');

        Route::get('/toast', [ToastController::class, 'index'])->name('toast.index');
        Route::post('/toast/sync', [ToastController::class, 'sync'])->name('toast.sync');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});
