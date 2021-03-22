<script>
    const cookieConsentDialog = window.CookieConsentDialog({
      acceptAllButton: true,
      collapsible: true,
      cookies: [
          {
              id: 'functional',
              label: '{{ __('Nevyhnutné') }}',
              description: '{{ __('Nevyhnutné na bezproblémový technický chod webu.') }}',
              required: true,

          },
          {
              id: 'analytical',
              label: '{{ __('Analytické') }}',
              description: '{{ __('Získavanie anonymizovaných štatistických údajov o používaní webovej stránky') }}',
              checked: true
          },
      ],
      labels: {
          title: '{{ __('Používanie cookies') }}',
          description: `{!! __('<p>Táto webstránka používa cookies, ktoré Vám umožňujú čo najpohodlnejšiu návštevu. <br><br>Vyberte si prosím, ktoré súbory cookies s nami chcete zdieľať. Vašim výberom nám dáte súhlas s ich spracúvaním. Nižšie uvedené kategórie cookies slúžia na zlepšovanie funkčnosti webstránky, komfortu pri používaní webstránky a prispôsobenie jej obsahu a reklám.</p>') !!}`,
          settingsLink: {
              text: '{{ __('Upraviť nastavenia') }}'
          },
          button: {
              default: '{{ __('Uložiť') }}',
              acceptAll: '{{ __('Povoliť všetky cookies') }}',
          },
      },
    });
</script>