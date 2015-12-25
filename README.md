# banner-bundle

## TODO

- Maybe port the simple banner generator over to this bundle?

## Usage

Having properly configured uglifycss and uglifyjs is a requirement for production.

Add this bundle to your AppKernel.php `new \Wucdbm\Bundle\BannerBundle\WucdbmBannerBundle()`

In your config.yml, add `WucdbmBannerBundle` to your assetic bundles, as well as your doctrine mapping (if not automatic)

Execute `app/console doctrine:schema:update --dump-sql` and after verifying what is being executed, execute again with --force.
Alternatively, use doctrine migrations via the DoctrineMigrations bundle.

Once this has been done, you can start using the bundle. Simply register it in your routing.yml in a protected area such as admin, like so:

```
wucdbm_banner:
    resource: "@WucdbmBannerBundle/Resources/config/routing.yml"
    prefix: /banners
```

You can create a link to the builder using `{{ path('wucdbm_banner_dashboard') }}`, or embed it into your admin UI via an iframe like so `<iframe src="{{ path('wucdbm_banner_dashboard') }}" style="border: 0; width: 100%; height: 100%;"></iframe>`

The User Interface is pretty self-explanatory. 
You create banners and positions.
Banners contain the JavaScript or HTML code. 
This can also be used for tracking or anything external you would like to add to your website via a UI in your admin section without thinking too much about its implementation.
Positions are placed in your code on your pages, like this `{{ 'YourPositionName'|banner }}` (There is also a twig function)
You can also print a link to the same page you're at, but with positions in debug mode, but only if your user has the `ROLE_ADMIN` role using `{{ showBannerPositionsUrl() }}`
If a position is lacking a banner, is inactive, the banner is inactive, or any other error, if you have `ROLE_ADMIN`, you will get an error message