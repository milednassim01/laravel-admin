<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;
use Encore\Admin\Form\Field\MultipleSelect;
use Encore\Admin\Form\Field\Select;
use Encore\Admin\Form\Field\Text;
use Encore\Admin\Grid;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class QuickCreate implements Renderable
{
    /**
     * @var Grid
     */
    protected $parent;

    /**
     * @var Collection
     */
    protected $fields;

    /**
     * QuickCreate constructor.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->parent = $grid;
        $this->fields = Collection::make();
    }

    protected function formatPlaceholder($placeholder)
    {
        return array_filter((array) $placeholder);
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Text
     */
    public function text($column, $placeholder = '')
    {
        $field = new Text($column, $this->formatPlaceholder($placeholder));

        $this->addField($field->width('200px'));

        return $field;
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Text
     */
    public function email($column, $placeholder = '')
    {
        return $this->text($column, $placeholder)
            ->inputmask(['alias' => 'email']);
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Text
     */
    public function ip($column, $placeholder = '')
    {
        return $this->text($column, $placeholder)
            ->inputmask(['alias' => 'ip'])
            ->width('120px');
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Text
     */
    public function url($column, $placeholder = '')
    {
        return $this->text($column, $placeholder)
            ->inputmask(['alias' => 'url']);
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Text
     */
    public function password($column, $placeholder = '')
    {
        return $this->text($column, $placeholder)
            ->attribute('type', 'password')
            ->width('100px');
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Text
     */
    public function mobile($column, $placeholder = '')
    {
        return $this->text($column, $placeholder)
            ->inputmask(['mask' => '99999999999'])
            ->width('100px');
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Text
     */
    public function integer($column, $placeholder = '')
    {
        return $this->text($column, $placeholder)
            ->inputmask(['alias' => 'integer'])
            ->width('120px');
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Select
     */
    public function select($column, $placeholder = '')
    {
        $field = new Select($column, $this->formatPlaceholder($placeholder));

        $this->addField($field);

        return $field;
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return MultipleSelect
     */
    public function multipleSelect($column, $placeholder = '')
    {
        $field = new MultipleSelect($column, $this->formatPlaceholder($placeholder));

        $this->addField($field);

        return $field;
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Field\Date
     */
    public function datetime($column, $placeholder = '')
    {
        return $this->date($column, $placeholder)->format('YYYY-MM-DD HH:mm:ss');
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Field\Date
     */
    public function time($column, $placeholder = '')
    {
        return $this->date($column, $placeholder)->format('HH:mm:ss');
    }

    /**
     * @param string $column
     * @param string $placeholder
     *
     * @return Field\Date
     */
    public function date($column, $placeholder = '')
    {
        $field = new Field\Date($column, $this->formatPlaceholder($placeholder));

        $this->addField($field);

        return $field;
    }

    /**
     * @param Field $field
     *
     * @return Field
     */
    protected function addField(Field $field)
    {
        $elementClass = array_merge(['quick-create'], $field->getElementClass());

        $field->addElementClass($elementClass);

        $field->setView($this->resolveView(get_class($field)));

        $this->fields->push($field);

        return $field;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected function resolveView($class)
    {
        $path = explode('\\', $class);

        $name = strtolower(array_pop($path));

        return "admin::grid.quick-create.{$name}";
    }

    /**
     * @param int $columnCount
     *
     * @return array|string
     */
    public function render($columnCount = 0)
    {
        if ($this->fields->isEmpty()) {
            return '';
        }

        return Admin::view('admin::grid.quick-create.form', [
            'columnCount' => $columnCount,
            'fields'      => $this->fields,
        ]);
    }
}
