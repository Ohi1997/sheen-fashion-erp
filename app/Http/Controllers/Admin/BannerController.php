<?php

namespace App\Http\Controllers\Admin;

use App\Actions\UploadBannerAction;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(private UploadBannerAction $upload)
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(15);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string'],
            'link_url' => ['nullable', 'url'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['required', 'image'],
        ]);

        $banner = Banner::create($data);
        $this->upload->execute($banner, $request->file('image'));

        return redirect()->route('admin.banners.index')->with('status', 'Banner created.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string'],
            'link_url' => ['nullable', 'url'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image'],
        ]);

        $banner->update($data);

        if ($request->hasFile('image')) {
            $this->upload->execute($banner, $request->file('image'));
        }

        return redirect()->route('admin.banners.index')->with('status', 'Banner updated.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return back()->with('status', 'Banner deleted.');
    }
}
