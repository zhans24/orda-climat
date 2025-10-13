<?php

// app/Providers/AppServiceProvider.php
namespace App\Providers;

use App\Models\ContactSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $c = ContactSettings::singleton();

            // Телефон для href
            $phoneRaw    = (string) ($c->phone ?? '');
            $phoneDigits = preg_replace('/\D+/', '', $phoneRaw) ?: '';
            $phoneHref   = $phoneDigits ? ('+' . $phoneDigits) : '';

            // WhatsApp: можно как ссылка или как номер
            $waRaw = trim((string) ($c->whatsapp ?? ''));
            if ($waRaw === '') {
                $waHref = '';
            } elseif (str_starts_with($waRaw, 'http://') || str_starts_with($waRaw, 'https://')) {
                $waHref = $waRaw;
            } else {
                $waDigits = preg_replace('/\D+/', '', $waRaw) ?: '';
                $waHref   = $waDigits ? 'https://wa.me/' . $waDigits : '';
            }

            // Хелпер для логинов/ссылок
            $buildLink = function (?string $raw, string $loginUrlPrefix): string {
                $raw = trim((string) ($raw ?? ''));
                if ($raw === '') return '';
                if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) return $raw;

                $login = ltrim($raw, '@ ');
                if ($login === '' || !preg_match('/^[A-Za-z0-9._-]+$/u', $login)) return '';
                return $loginUrlPrefix . $login;
            };

            $instaHref  = $buildLink((string) ($c->instagram ?? ''), 'https://instagram.com/');
            $tiktokHref = $buildLink((string) ($c->tiktok ?? ''), 'https://www.tiktok.com/@');

            // Айфрейм карты как есть (если пусто — на вьюхе подставится дефолт)
            $mapIframe = trim((string) ($c->map_iframe ?? ''));

            // --- Авто-хлебные крошки ---
            // 1) Если во вьюхе уже есть $breadcrumbs — не трогаем (можно переопределять локально)
            $data = $view->getData();
            $breadcrumbs = $data['breadcrumbs'] ?? null;

            // 2) Если есть $page (PageController) — строим Home + $page->title
            if ($breadcrumbs === null && isset($data['page']) && ($title = ($data['page']->title ?? null))) {
                $breadcrumbs = [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => $title,    'active' => true],
                ];
            }

            if ($breadcrumbs === null) {
                $viewName = $view->getName(); // например: pages.contacts
                $map = [
                    'pages.contacts' => 'Контакты',
                    'pages.about'    => 'О компании',
                    'pages.privacy'  => 'Политика конфиденциальности',
                    'pages.home'     => 'Главная', // обычно крошки там не нужны, но пусть будет
                ];
                if (isset($map[$viewName]) && $viewName !== 'pages.home') {
                    $breadcrumbs = [
                        ['title' => 'Главная', 'url' => route('home')],
                        ['title' => $map[$viewName], 'active' => true],
                    ];
                }
            }

            $emailRaw  = trim((string) ($c->email ?? ''));
            $emailHref = filter_var($emailRaw, FILTER_VALIDATE_EMAIL) ? ('mailto:' . $emailRaw) : '';

            $view->with([
                'contacts'   => $c,
                'phoneHref'  => $phoneHref,
                'waHref'     => $waHref,
                'instaHref'  => $instaHref,
                'tiktokHref' => $tiktokHref,
                'addr'       => (string) ($c->address ?? ''),
                'emailHref' => $emailHref,
                'mapIframe'  => $mapIframe,

                // хлебные крошки (если вычислили)
                'breadcrumbs' => $breadcrumbs,
            ]);
        });
    }
}
