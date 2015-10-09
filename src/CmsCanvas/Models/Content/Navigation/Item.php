<?php 

namespace CmsCanvas\Models\Content\Navigation;

use CmsCanvas\Database\Eloquent\Model;
use CmsCanvas\Content\Navigation\Item\Render;
use CmsCanvas\Content\Navigation\Item\RenderCollection;
use CmsCanvas\Content\Navigation\Builder\Item as ItemBuilder;

class Item extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'navigation_items';

    /**
     * The columns that can be mass-assigned.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'entry_id',
        'url',
        'type',
        'id_attribute',
        'class_attribute',
        'target_attribute',
        'children_visibility_id',
        'disable_current_flag',
        'disable_current_ancestor_flag',
        'hidden_flag',
    ];

    /**
     * The columns that can NOT be mass-assigned.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Set true if the item links to the current url
     *
     * @var bool
     */
    protected $currentItemFlag = false;

    /**
     * Set true if the item is an ancestor of the current item 
     *
     * @var bool
     */
    protected $currentItemAncestorFlag = false;

    /**
     * Set true if the item is solo or first in a collection
     *
     * @var bool
     */
    protected $firstFlag = false;

    /**
     * Set true if the item is solo or last in a collection
     *
     * @var bool
     */
    protected $lastFlag = false;

    /**
     * Defines a one to many relationship with navigation items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('CmsCanvas\Models\Content\Navigation\Item', 'parent_id', 'id');
    }

    /**
     * Defines a one to one relationship with navigation items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent()
    {
        return $this->hasOne('CmsCanvas\Models\Content\Navigation\Item', 'id', 'parent_id');
    }

    /**
     * Defines a one to many relationship with entries
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function entry()
    {
        return $this->hasOne('CmsCanvas\Models\Content\Entry', 'id', 'entry_id');
    }

    /**
     * Creates a new builder item instance
     *
     * @return \CmsCanvas\Content\Navigation\Builder\Item
     */
    public function newItemBuilder()
    {
        return new ItemBuilder($this);
    }

    /**
     * Creates new builder item instances for a collection 
     *
     * @param  \CmsCanvas\Models\Content\Navigation\Item|collection
     * @return \CmsCanvas\Content\Navigation\Builder\Item|array
     */
    public static function newItemBuilderCollection($items)
    {
        $itemBuilders = [];
        $itemCount = count($items);
        $counter = 1;

        foreach ($items as $item) {
            $itemBuilder = $item->newItemBuilder();

            $itemBuilder->setIndex($counter - 1);

             if ($counter !== 1) {
                $itemBuilder->setFirstFlag(false);
            }

            if ($counter !== $itemCount) {
                $itemBuilder->setLastFlag(false);
            }

            $itemBuilders[] = $itemBuilder;
            $counter++;
        }

        return $itemBuilders;
    }

    /**
     * Returns a render instance
     *
     * @return \CmsCanvas\Content\Navigation\Item\Render
     */
    public function render()
    {
        $this->newItemBuilder()->render();
    }

    /**
     * Returns children only if it has already been loaded
     *
     * @return \CmsCanvas\Models\Content\Navigation\Item|collection
     */
    public function getLoadedChildren()
    {
        if ($this->isChildrenLoaded()) {
            return $this->children;
        }

        return $this->newCollection(); 
    }

    /**
     * Checks if children has been loaded
     *
     * @return bool
     */
    public function isChildrenLoaded() 
    {
        return isset($this->relations['children']);
    }

}