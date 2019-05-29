<?php namespace Depcore\Services\Models;

use Model;
use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;

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
        'slug',
    ];


    public $jsonable = ['content_blocks'];

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
    protected $fillable = ['name','content'];

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
    public function scopePublished($query)
    {
        return $query->whereNotNull('published')->where ( 'published',true );
    }

    /**
     * Get sibling elements for selected service
     *
     * @return array
     * @author Adam
     **/
    public function scopeSiblings($query)
    {
        return $query->published()->where('id', '<>', $this->id)->get(  );
    }

    public static function getMenuTypeInfo($type)
    {
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
                if (!$page->hasComponent('ServicesList')) continue;

                $properties = $page->getComponentProperties('ServicesList');

                if (!isset($properties['url']) ||  !isset($properties['slug'])) continue;

                $cmsPages[] = $page;
            }

            $result['cmsPages'] = $cmsPages;
        }

        return $result;
    }

    public static function resolveMenuItem($item, $url, $theme)
    {
        $pageName = 'uslugi';

        $theme = Theme::getActiveTheme();

        $pages = CmsPage::listInTheme($theme, true);
        $cmsPages = [];

        foreach ($pages as $page) {
            if (!$page->hasComponent('ServicesList')) continue;

            $properties = $page->getComponentProperties('ServicesList');

            if ( !isset($properties['url'] ) and !isset( $properties['slug']) ) continue;
            $pageName = strtolower($page->fileName);
        }

        $cmsPage = \Cms\Classes\Page::loadCached($theme, $pageName);

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
}