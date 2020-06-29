<?php namespace Depcore\Services\Models;

use Model;
use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;
use RainLab\Translate\Classes\Translator;
/**
 * Service Model
 */
class Service extends Model
{
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = [
        'name',
        'short_description',
        'meta_title',
        'meta_description',
        'content',
        'content_blocks',
        ['slug','index'=>true],
    ];


    public $jsonable = ['content_blocks','content'];

    public $rules = [
        'name' => 'required',
        'short_description' => 'required',
        'content' => 'required',
        'meta_title' => 'max:70',
        'meta_description' => 'max:160',
    ];

    public $slugs = ['slug' => 'name'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'depcore_services_services';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['name','content','short_description','content_blocks','sort_order','meta_title','meta_description'];

    /**
     * @var array Relations
     */

    public $attachOne = [
        'backgroundImage' => '\System\Models\File',
        'icon' => '\System\Models\File',
    ];


    /**
     * get published services
     *
     * @return array
     * @author Adam
     **/
    public function scopePublished($query) {
        return $query->whereNotNull('published')->where ( 'published',true )->orderBy( 'sort_order' );
    }

    /**
     * Get sibling elements for selected service
     *
     * @return array
     * @author Adam
     **/
    public function scopeSiblings($query) {
        return $query->published()->where('id', '<>', $this->id)->get(  );
    }

    public function url(  ) {
        $translator = Translator::instance();
        $locale = $translator->getLocale(true);
        $theme = Theme::getActiveTheme();

        $cmsPage = CmsPage::loadCached($theme, 'usluga');
        $pageUrl = $cmsPage->localeUrl ? $cmsPage->url : $cmsPage->localeUrl[$locale];

        $router = new \October\Rain\Router\Router;
        return  '/' . $locale. $router->urlFromPattern($pageUrl, ['slug' => $this->slug]);
        return  \Config::get('app.url') . "$locale/usluga/{$this->slug}";
    }

    public function scopeNext( $query ){
        $temp = Service::where( 'published',1 )->
                              where( 'sort_order','>',$this->sort_order )->
                              where( 'name','<>',$this->name )->
                              first (  );

        $firstService = Service::published()->first();

        return empty($temp) ? $firstService : $temp;
    }

    public static function getMenuTypeInfo($type) {
        $result = [];

        if ($type == 'single-service') {
            $references = [];

            $items = self::published()->orderBy('name')->get();
            foreach ($items as $item) {
                $references[$item->id] = str_replace('|', '', $item->name);
            }

            $result = [
                'references'   => $references,
                'nesting'      => false,
                'dynamicItems' => false
            ];
        }

        if ($type == 'all-services')
            $result = [
                'dynamicItems' => true
            ];

        if ($result) {
            $theme = Theme::getActiveTheme();

            $pages = CmsPage::listInTheme($theme, true);
            $cmsPages = [];

            foreach ($pages as $page) {
                if (!$page->hasComponent('ServiceContent')) continue;

                $properties = $page->getComponentProperties('ServiceContent');

                // if (!isset($properties['url']) || !isset($properties['slug'])) continue;

                $cmsPages[] = $page;
            }

            $result['cmsPages'] = $cmsPages;
        }

        return $result;
    }

    public static function translateParams($params, $oldLocale, $newLocale) {
        $newParams = $params;
        foreach ($params as $paramName => $paramValue) {
            $records = self::transWhere($paramName, $paramValue, $oldLocale)->first();
            if ($records) {
                $records->translateContext($newLocale);
                $newParams[$paramName] = $records->$paramName;
            }
        }
        return $newParams;
    }

    public static function resolveMenuItem($item, $url, $theme) {
        $service = self::find( $item->reference );

        if ($item->type == 'single-service') {

            $translator = Translator::instance();

            $locale = $translator->getLocale(true);
            $cmsPage = CmsPage::loadCached($theme, $item->cmsPage);
            $pageUrl = $cmsPage->localeUrl ? $cmsPage->url : $cmsPage->localeUrl[$locale];

            $router = new \October\Rain\Router\Router;
            $result['url'] =  '/' . $locale. $router->urlFromPattern($pageUrl, ['slug' => $service->slug]);

            $result['isActive'] = strpos($url, $service->slug);
            $result['mtime'] = $service->updated_at;
            return $result;
        }

        $pageName = '';
        $theme = Theme::getActiveTheme();
        $pages = CmsPage::listInTheme($theme, true);

        $cmsPages = [];
        $cmsPage = \Cms\Classes\Page::loadCached($theme, $pageName);

        foreach ($pages as $page) {
            if (!$page->hasComponent('ServiceContent')) continue;

            $properties = $page->getComponentProperties('ServiceContent');

            if ( !isset($properties['url'] ) and !isset( $properties['slug']) ) continue;
            $pageName = strtolower($page->fileName);
        }

        $items = self::orderBy('created_at', 'DESC')->get()->map(function (self $item) use ($cmsPage, $url, $pageName) {
                $pageUrl = $cmsPage->url($pageName, ['slug' => $item->slug]);

                return [
                    'title'    => $item->name,
                    'url'      => $pageUrl.'/'.$item->slug,
                    'mtime'    => $item->updated_at,
                    'isActive' => $item->is_published,
                ];

            })->toArray();

        return [
            'items' => $items,
        ];
    }

    protected function getCmsPageUrl(  ) {

    }

    protected function getSingleMenuItem(  ) {

    }

    /**
     * Insert a non breaking space before certain characters
     *
     * @return void
     * @author Adam
     **/
    public function terms()   {
        $terms = array('al.','albo','ale','ależ','b.','bez','bm.','bp','br.','by','bym','byś','bł.','cyt.','cz.','czy','czyt.','dn.','do','doc.','dr','ds.','dyr.','dz.','fot.','gdy','gdyby','gdybym','gdybyś','gdyż','godz.','im.','inż.','jw.','kol.','komu','ks.','która','którego','której','któremu','który','których','którym','którzy','lecz','lic.','m.in.','max','mgr','min','moich','moje','mojego','mojej','mojemu','mych','mój','na','nad','nie','niech','np.','nr','nr.','nrach','nrami','nrem','nrom','nrowi','nru','nry','nrze','nrze','nrów','nt.','nw.','od','oraz','os.','p.','pl.','pn.','po','pod','pot.','prof.','przed','przez','pt.','pw.','pw.','tak','tamtej','tamto','tej','tel.','tj.','to','twoich','twoje','twojego','twojej','twych','twój','tylko','ul.','we','wg','woj.','więc','za','ze','śp.','św.','że','żeby','żebyś','—',);

        return $terms;

    }


    // public function beforeSave(  ) {

    //     // Keep numbers together - this is independed of current language
    //     $numbers = $this->is_on( 'numbers' );
    //     if ( $numbers ) {
    //         preg_match_all( '/(>[^<]+<)/', $content, $parts );
    //         if ( $parts && is_array( $parts ) && ! empty( $parts ) ) {
    //             $parts = array_shift( $parts );
    //             foreach ( $parts as $part ) {
    //                 $to_change = $part;
    //                 while ( preg_match( '/(\d+) ([\da-z]+)/i', $to_change, $matches ) ) {
    //                     $to_change = preg_replace( '/(\d+) ([\da-z]+)/i', '$1&nbsp;$2', $to_change );
    //                 }
    //                 if ( $part != $to_change ) {
    //                     $content = str_replace( $part, $to_change, $content );
    //                 }
    //             }
    //         }
    //     }

    //     $terms = $this->terms();

    //     // Avoid to replace inside script or styles tags
    //     preg_match_all( '@(<(script|style)[^>]*>.*?(</(script|style)>))@is', $content, $matches );
    //     $exceptions = array();
    //     if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
    //         $salt = 'kQc6T9fn5GhEzTM3Sxn7b9TWMV4PO0mOCV06Da7AQJzSJqxYR4z3qBlsW9rtFsWK';
    //         foreach ( $matches[0] as $one ) {
    //             $key = sprintf( '<!-- %s %s -->', $salt, md5( $one ) );
    //             $exceptions[ $key ] = $one;
    //             $re = sprintf( '@%s@', preg_replace( '/@/', '\@', preg_quote( $one, '/' ) ) );
    //             $content = preg_replace( $re, $key, $content );
    //         }
    //     }

    //     // base therms replace
    //     $re = '/^([aiouwz]|'.preg_replace( '/\./', '\.', implode( '|', $terms ) ).') +/i';
    //     $content = preg_replace( $re, '$1$2&nbsp;', $content );

    //     // single letters
    //     $re = '/([ >\(]+|&nbsp;)([aiouwz]|'.preg_replace( '/\./', '\.', implode( '|', $terms ) ).') +/i';

    //     // double call to handle orphan after orphan after orphan
    //     $content = preg_replace( $re, '$1$2&nbsp;', $content );
    //     $content = preg_replace( $re, '$1$2&nbsp;', $content );

    //     // single letter after previous orphan
    //     $re = '/(&nbsp;)([aiouwz]) +/i';
    //     $content = preg_replace( $re, '$1$2&nbsp;', $content );

    //     if ( ! empty( $exceptions ) && is_array( $exceptions ) ) {
    //         foreach ( $exceptions as $key => $one ) {
    //             $re = sprintf( '/%s/', $key );
    //             $content = preg_replace( $re, $one, $content );
    //         }
    //     }

    //     return $content;
    // }

}