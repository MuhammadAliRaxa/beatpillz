<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(translate('Home'), route('home'));
});

Breadcrumbs::for('premium', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(translate('Premium'), route('premium.index'));
});

Breadcrumbs::for('contact', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(translate('Contact US'), route('contact'));
});

Breadcrumbs::for('favorites', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(translate('Favorites'), route('favorites'));
});

Breadcrumbs::for('categories', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(translate('Categories'), route('categories.index'));
});

Breadcrumbs::for('categories.category', function (BreadcrumbTrail $trail, $category) {
    $trail->parent('categories');
    $trail->push($category->name, $category->getLink());
});

Breadcrumbs::for('categories.sub-category', function (BreadcrumbTrail $trail, $category, $subCategory) {
    $trail->parent('categories.category', $category);
    $trail->push($subCategory->name, $subCategory->getLink());
});

Breadcrumbs::for('items', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(translate('Items'), route('items.index'));
});

Breadcrumbs::for('items.view', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('items');
    $trail->push($item->category->name, $item->category->getLink());
    if ($item->subCategory) {
        $trail->push($item->subCategory->name, $item->subCategory->getLink());
    }
    $trail->push($item->name, $item->getLink());
});

Breadcrumbs::for('items.reviews', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('items.view', $item);
    $trail->push(translate('Reviews'), $item->getReviewsLink());
});

Breadcrumbs::for('items.reviews.review', function (BreadcrumbTrail $trail, $item, $review) {
    $trail->parent('items.reviews', $item);
    $trail->push($review->id, $review->getLink());
});

Breadcrumbs::for('items.comments', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('items.view', $item);
    $trail->push(translate('Comments'), $item->getCommentsLink());
});

Breadcrumbs::for('items.comments.comment', function (BreadcrumbTrail $trail, $item, $comment) {
    $trail->parent('items.comments', $item);
    $trail->push($comment->id, $comment->getLink());
});

Breadcrumbs::for('items.changelogs', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('items.view', $item);
    $trail->push(translate('Changelogs'), $item->getChangeLogsLink());
});

Breadcrumbs::for('items.support', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('items.view', $item);
    $trail->push(translate('Support'), $item->getSupportLink());
});

Breadcrumbs::for('cart', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(translate('Cart'), route('cart.index'));
});

Breadcrumbs::for('checkout', function (BreadcrumbTrail $trail, $transaction) {
    $trail->parent('cart');
    $trail->push(translate('Checkout'), route('checkout.index', $transaction->id));
});

Breadcrumbs::for('page', function (BreadcrumbTrail $trail, $page) {
    $trail->parent('home');
    $trail->push($page->title, $page->getLink());
});

Breadcrumbs::for('help', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(translate('Help Center'), route('help.index'));
});

Breadcrumbs::for('help_category', function (BreadcrumbTrail $trail, $helpCategory) {
    $trail->parent('help');
    $trail->push($helpCategory->name, $helpCategory->getLink());
});

Breadcrumbs::for('help_article', function (BreadcrumbTrail $trail, $helpArticle) {
    $trail->parent('help_category', $helpArticle->category);
    $trail->push($helpArticle->title, $helpArticle->getLInk());
});

Breadcrumbs::for('blog', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(translate('Blog'), route('blog.index'));
});

Breadcrumbs::for('blog_category', function (BreadcrumbTrail $trail, $blogCategory) {
    $trail->parent('blog');
    $trail->push($blogCategory->name, route('blog.category', $blogCategory->slug));
});

Breadcrumbs::for('blog_article', function (BreadcrumbTrail $trail, $blogArticle) {
    $trail->parent('blog_category', $blogArticle->category);
    $trail->push($blogArticle->title, $blogArticle->getLInk());
});

Breadcrumbs::for('workspace', function (BreadcrumbTrail $trail) {
    $trail->push(translate('Workspace'), route('workspace.index'));
});

Breadcrumbs::for('workspace.dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('Dashboard'), route('workspace.dashboard'));
});

Breadcrumbs::for('workspace.items', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('My Items'), route('workspace.items.index'));
});

Breadcrumbs::for('workspace.items.create', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.items');
    $trail->push(translate('New Item'), route('workspace.items.create'));
});

Breadcrumbs::for('workspace.items.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('workspace.items');
    $trail->push($item->id, route('workspace.items.edit', $item->id));
});

Breadcrumbs::for('workspace.items.changelogs.index', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('workspace.items.edit', $item);
    $trail->push(translate('Changelogs'), route('workspace.items.changelogs.index', $item->id));
});

Breadcrumbs::for('workspace.items.history', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('workspace.items.edit', $item);
    $trail->push(translate('History'), route('workspace.items.history', $item->id));
});

Breadcrumbs::for('workspace.items.discount', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('workspace.items.edit', $item);
    $trail->push(translate('Discount'), route('workspace.items.discount', $item->id));
});

Breadcrumbs::for('workspace.items.free', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('workspace.items.edit', $item);
    $trail->push(translate('Free Item'), route('workspace.items.free', $item->id));
});

Breadcrumbs::for('workspace.items.statistics', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('workspace.items.edit', $item);
    $trail->push(translate('Statistics'), route('workspace.items.statistics', $item->id));
});

Breadcrumbs::for('workspace.purchases', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('Purchases'), route('workspace.purchases.index'));
});

Breadcrumbs::for('workspace.transactions', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('Transactions'), route('workspace.transactions.index'));
});

Breadcrumbs::for('workspace.transactions.show', function (BreadcrumbTrail $trail, $trx) {
    $trail->parent('workspace.transactions');
    $trail->push($trx->id, route('workspace.transactions.show', $trx->id));
});

Breadcrumbs::for('workspace.referrals', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('Referrals'), route('workspace.referrals'));
});

Breadcrumbs::for('workspace.withdrawals', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('Withdrawals'), route('workspace.withdrawals.index'));
});

Breadcrumbs::for('workspace.balance.index', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('My Balance'), route('workspace.balance.index'));
});

Breadcrumbs::for('workspace.refunds.index', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('Refunds'), route('workspace.refunds.index'));
});

Breadcrumbs::for('workspace.refunds.create', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.refunds.index');
    $trail->push(translate('Request a Refund'), route('workspace.refunds.create'));
});

Breadcrumbs::for('workspace.refunds.show', function (BreadcrumbTrail $trail, $refund) {
    $trail->parent('workspace.refunds.index');
    $trail->push($refund->id, route('workspace.refunds.show', $refund->id));
});

Breadcrumbs::for('workspace.tickets.index', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('Tickets'), route('workspace.tickets.index'));
});

Breadcrumbs::for('workspace.tickets.create', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.tickets.index');
    $trail->push(translate('New Ticket'), route('workspace.tickets.create'));
});

Breadcrumbs::for('workspace.tickets.show', function (BreadcrumbTrail $trail, $ticket) {
    $trail->parent('workspace.tickets.index');
    $trail->push($ticket->id, route('workspace.tickets.show', $ticket->id));
});

Breadcrumbs::for('workspace.tools', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('Tools'), route('workspace.tools.index'));
});

Breadcrumbs::for('workspace.tools.license-verification', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.tools');
    $trail->push(translate('License Verification'), route('workspace.tools.license-verification.index'));
});

Breadcrumbs::for('workspace.settings', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace');
    $trail->push(translate('Settings'), route('workspace.settings.index'));
});

Breadcrumbs::for('workspace.settings.profile', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.settings');
    $trail->push(translate('Profile details'), route('workspace.settings.profile'));
});

Breadcrumbs::for('workspace.settings.withdrawal', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.settings');
    $trail->push(translate('Withdrawal Details'), route('workspace.settings.withdrawal'));
});

Breadcrumbs::for('workspace.settings.subscription', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.settings');
    $trail->push(translate('Subscription'), route('workspace.settings.subscription'));
});

Breadcrumbs::for('workspace.settings.badges', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.settings');
    $trail->push(translate('My Badges'), route('workspace.settings.badges'));
});

Breadcrumbs::for('workspace.settings.api-key', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.settings');
    $trail->push(translate('API Key'), route('workspace.settings.api-key'));
});

Breadcrumbs::for('workspace.settings.password', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.settings');
    $trail->push(translate('Change Password'), route('workspace.settings.password'));
});

Breadcrumbs::for('workspace.settings.2fa', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.settings');
    $trail->push(translate('2FA Authentication'), route('workspace.settings.2fa'));
});

Breadcrumbs::for('workspace.settings.kyc', function (BreadcrumbTrail $trail) {
    $trail->parent('workspace.settings');
    $trail->push(translate('KYC Verification'), route('workspace.settings.kyc'));
});
