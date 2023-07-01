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
        return Admin::content(function (Content $���ū�) {

            $���ū�->header('分销商品');
            $���ū�->description('分销商品列表');

            $���ū�->body($this->grid());
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid() {
        return Admin::grid(Goods::class, function (Grid $���Ⱦ�) {

            $���Ⱦ�->model()->addConditions([
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

            $���Ⱦ�->disableCreation();

            $���Ⱦ�->actions(function (Grid\Displayers\Actions $��ɣ�) {
                $��ɣ�->disableDelete();
                $��ɣ�->disableEdit();
                $���ə� = $��ɣ�->row->goods_id;
                $��ɣ�->append('<a href="/admin/goods/' . $���ə� . '/edit"><i class="fa fa-edit"></i></a>');
                $������ = Goods::find($���ə�);
                if (count($������->spec_values) > 0) {
                    $��ɣ�->append(' | <a href="/admin/goodsSpecStorage?&goods_id=' . $���ə� . '"><i class="fa fa-copy"></i></a>');
                }
            });

            $���Ⱦ�->tools(function (Grid\Tools $����ӈ) {
                $����ӈ->batch(function (Grid\Tools\BatchActions $��ɣ�) {
                    $��ɣ�->disableDelete();
                });
            });

            $���Ⱦ�->goods_id('商品ID')->sortable();
            $���Ⱦ�->goods_sku('商品SKU');

            $���Ⱦ�->gc_id('商品分类')->display(function ($������) {
                return Category::find($������)->gc_name;
            })->label('primary');

            $���Ⱦ�->brand_id('商品品牌')->display(function ($���ư�) {
                return Brand::find($���ư�)->brand_name;
            })->label();

            $���Ⱦ�->goods_name('商品名称');
            $���Ⱦ�->commission('商品佣金')->display(function ($㶷���) {
                return $㶷��� . ' %';
            });
            $���Ⱦ�->is_hot('是否推荐')->switch();
            $���Ⱦ�->is_new('是否新品')->switch();
            $���Ⱦ�->status('商品状态')->switch();

            $���Ⱦ�->filter(function ($�۠���) {
                $�۠���->disableIdFilter();
                $�۠���->is('gc_id', '商品分类')->select(Category::selectOptions());
                $�۠���->like('goods_name', '商品名称');
                $�۠���->like('goods_sku', '商品SKU');
            });

        });
    }
}
