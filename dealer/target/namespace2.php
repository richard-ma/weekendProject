<?php
/**
 * User: djunny
 * Date: 2017-08-30
 * Time: 17:45
 * Mail: 199962760@qq.com
 */
namespace App\Admin\Controllers;

class Controller
{

}


class DistributeGoodsController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index() {
        return Admin::content(function (Content $şÂæÅ«Œ) {

            $şÂæÅ«Œ->header('åˆ†é”€å•†å“');
            $şÂæÅ«Œ->description('åˆ†é”€å•†å“åˆ—è¡¨');

            $şÂæÅ«Œ->body($this->grid());
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid() {
        return Admin::grid(Goods::class, function (Grid $ŠÃÓÈ¾¢) {

            $ŠÃÓÈ¾¢->model()->addConditions([
                [
                    'orderBy' => [
                        'goods_id',
                        'desc',
                    ],
                ],
                [
                    'where' => [
                        'commission',
                        '>',
                        '0',
                    ],
                ],
            ]);

            $ŠÃÓÈ¾¢->disableCreation();

            $ŠÃÓÈ¾¢->actions(function (Grid\Displayers\Actions $˜ã«É£İ) {
                $˜ã«É£İ->disableDelete();
                $˜ã«É£İ->disableEdit();
                $²şÆÉ™ø = $˜ã«É£İ->row->goods_id;
                $˜ã«É£İ->append('<a href="/admin/goods/' . $²şÆÉ™ø . '/edit"><i class="fa fa-edit"></i></a>');
                $ßéÁ§¯– = Goods::find($²şÆÉ™ø);
                if (count($ßéÁ§¯–->spec_values) > 0) {
                    $˜ã«É£İ->append(' | <a href="/admin/goodsSpecStorage?&goods_id=' . $²şÆÉ™ø . '"><i class="fa fa-copy"></i></a>');
                }
            });

            $ŠÃÓÈ¾¢->tools(function (Grid\Tools $±½ÀìÓˆ) {
                $±½ÀìÓˆ->batch(function (Grid\Tools\BatchActions $˜ã«É£İ) {
                    $˜ã«É£İ->disableDelete();
                });
            });

            $ŠÃÓÈ¾¢->goods_id('å•†å“ID')->sortable();
            $ŠÃÓÈ¾¢->goods_sku('å•†å“SKU');

            $ŠÃÓÈ¾¢->gc_id('å•†å“åˆ†ç±»')->display(function ($°“Æşö¦) {
                return Category::find($°“Æşö¦)->gc_name;
            })->label('primary');

            $ŠÃÓÈ¾¢->brand_id('å•†å“å“ç‰Œ')->display(function ($ˆ¬áÆ°·) {
                return Brand::find($ˆ¬áÆ°·)->brand_name;
            })->label();

            $ŠÃÓÈ¾¢->goods_name('å•†å“åç§°');
            $ŠÃÓÈ¾¢->commission('å•†å“ä½£é‡‘')->display(function ($ã¶·ĞÀ“) {
                return $ã¶·ĞÀ“ . ' %';
            });
            $ŠÃÓÈ¾¢->is_hot('æ˜¯å¦æ¨è')->switch();
            $ŠÃÓÈ¾¢->is_new('æ˜¯å¦æ–°å“')->switch();
            $ŠÃÓÈ¾¢->status('å•†å“çŠ¶æ€')->switch();

            $ŠÃÓÈ¾¢->filter(function ($™Û õ›ç) {
                $™Û õ›ç->disableIdFilter();
                $™Û õ›ç->is('gc_id', 'å•†å“åˆ†ç±»')->select(Category::selectOptions());
                $™Û õ›ç->like('goods_name', 'å•†å“åç§°');
                $™Û õ›ç->like('goods_sku', 'å•†å“SKU');
            });

        });
    }
}
