<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\MenuItem;

class MenuBuilderController extends Controller
{


    public function index($id)
    {
        //return 'menu';
        Gate::authorize('app.menus.builder');
        $menu = Menu::findOrFail($id);
        return view('backend.menu.builder', compact('menu'));
    }

    public function order(Request $request, $id)
    {

        $menuItemOrder = json_decode($request->get('order'));
       $this->orderMenu($menuItemOrder,null);


    }

    private function orderMenu( array $menuItem, $parentId)
    {
        foreach ($menuItem as $key => $Item) {
            MenuItem::findOrFail($Item->id)->update([
                'order' => $key + 1,
                'parent_id' => $parentId
            ]);
            if (isset($Item->children)) {

                $this->orderMenu($Item->children, $Item->id);
            }
        }
    }

    public function itemCreate($id)
    {
        Gate::authorize('app.menus.create');
        $menu = Menu::findOrFail($id);
        return view('backend.menu.item.form', compact('menu'));
    }

    public function itemStore(Request  $request, $id)
    {
        Gate::authorize('app.menus.create');
        $this->validate($request, [
            'divider_title' => 'nullable |string',
            'title' => 'nullable|string',
            'url' => 'nullable|string',
            'target' => 'nullable|string',
            'icon_class' => 'nullable|string'
        ]);

        $menu = Menu::findOrFail($id);
        MenuItem::create([
            'menu_id' => $menu->id,
            'type' => $request->type,
            'title' => $request->title,
            'divider_title' => $request->divider_title,
            'url' => $request->url,
            'target' => $request->target,
            'icon_class' => $request->icon_class
        ]);
        notify()->success('Menu Item Successfully Added.', 'Added');
        return redirect()->route('app.menus.builder', $menu->id);
    }
    public function itemEdit(Request  $request, $menuId, $itemId)
    {
        Gate::authorize('app.menus.edit');
        $menu = Menu::findOrFail($menuId);
        $menuItem = $menu->menuItems()->findOrFail($itemId);
        return view('backend.menu.item.form', compact('menu', 'menuItem'));
    }

    public function itemUpdate(Request $request, $menuId, $itemId)
    {
        Gate::authorize('app.menus.edit');
        $this->validate($request, [
            'divider_title' => 'nullable |string',
            'title' => 'nullable|string',
            'url' => 'nullable|string',
            'target' => 'nullable|string',
            'icon_class' => 'nullable|string',
            'type' => 'required|string'
        ]);

        $menu = Menu::findOrFail($menuId);
        $menu->menuItems()->findOrFail($itemId)->update([
            'type' => $request->type,
            'title' => $request->title,
            'divider_title' => $request->divider_title,
            'url' => $request->url,
            'target' => $request->target,
            'icon_class' => $request->icon_class
        ]);
        notify()->success('Menu Item Successfully Updated.', 'Updated');
        return redirect()->route('app.menus.builder', $menu->id);
    }

    public function itemDestroy($menuId, $itemId)
    {

        Gate::authorize('app.menus.destroy');
        Menu::findOrFail($menuId)
            ->menuItems()
            ->findOrFail($itemId)
            ->delete();
        notify()->success('Menu Item Successfully Deleted.', 'Deleted');
        return back();
    }
}
