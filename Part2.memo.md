# Laravel CRUD system tutorial sheet

## Install Laravel 8 via installer

コマンド入力画面を開いて、Laravel をインストールするコマンドを入力

```command
laravel new project_name --jet
```

`--jet`フラグを記入することで Jetstream 環境を Laravel 本体と一緒にインストールできます。

次に、Jetstream の view ファイルを手軽に設定できるように、resource フォルダ以下にコピーするコマンドを入力します。

```command
php artisan vendor:publish --tag=jetstream-views
```

Jetstream の view ファイル群がコピーされたら、次に Page モデルとそのマイグレーションファイルを作成します。

```command
php artisan make:model Page -m
```

作成されたマイグレーションファイルを開いて（～\_create_pages_table.php）up メソッドの部分を編集します。

```diff
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
+          $table->string('title')->nullable();
+          $table->string('slug')->nullable();
+          $table->longText('content')->nullable();
            $table->timestamps();
        });
    }
```

モデル page のマイグレーションファイルを編集したら、以下のコマンドで pages テーブルを作成しましょう。

```command
php artisan migrate
```

一緒に livewire のコンポーネントも作成します。

```command
php artisan make:livewire Pages
php artisan make:livewire Frontpage
```

`resources/views/admin` に `pages.blade.php`を追加するよ

```php:pages.blade.php
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                The Pages page.
            </div>
        </div>
    </div>
</x-app-layout>
```

`resources/views/navigation-menu.blade.php` の 13 行目あたりを編集します。メニューバーに「Pages」のリンクが表示されました！

```php
<!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-jet-nav-link>
    <x-jet-nav-link href="{{ route('pages') }}" :active="request()->routeIs('pages')">
        {{ __('Pages') }}
    </x-jet-nav-link>
</div>
```

Page モデルファイルを開いて編集します。

```diff
class Page extends Model
{
    use HasFactory;

+  protected $guarded = [];
}
```

そして、`resources/views/admin/pages.blade.php` を更に編集します。

```diff
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
+              @livewire('pages')
            </div>
        </div>
    </div>
</x-app-layout>
```

`resources/views/livewire/pages.blade.php` も編集するよ。

```html
<div>
    <p>Pages livewwire component</p>
</div>
```

ここでいったんブラウザで確認してみると`pages.blade.php` の `@livewire('pages')` の部分によって`resources\views\livewire\pages.blade.php`のファイルが取り込まれていることが分かります。

@livewire ディレクティブによって、`resources\views\livewire\`以下のファイルが読み込まれます。読み込まれるファイルは引数内の文字列で設定できます。

livewire コンポーネントの`app\Http\Livewire\Pages.php`を編集しましょう

```diff
<?php

namespace App\Http\Livewire;

use Livewire\Component;
+ use App\Models\Page;

class Pages extends Component
{

+    public $modalFormVisible = false;
+    public $slug;
+    public $title;
+    public $content;
+
+    public function createShowModal()
+    {
+        $this->modalFormVisible = true;
+    }

    public function render()
    {
        return view('livewire.pages');
    }
}
```

`resources\views\livewire\pages.blade.php`も編集します。モーダルウィンドウの表示のためのコードを書きます。

```diff
<div class="p-6">
+    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
            <x-jet-button wire:click="createShowModal">
                {{ __('Create') }}
            </x-jet-button>
+    </div>

+   {{-- <!-- Modal Form -- > --}}
+   <x-jet-dialog-modal wire:model="modalFormVisible">
+       <x-slot name="title">
+           {{ __('Save Page') }}
+       </x-slot>
+
+       <x-slot name="content">
+           The form elements goes here
+       </x-slot>
+
+       <x-slot name="footer">
+           <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
+               {{ __('Cancel') }}
+           </x-jet-secondary-button>
+
+           <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
+               {{ __('Save') }}
+           </x-jet-button>
+       </x-slot>
+   </x-jet-dialog-modal>

</div>
```

続いて、`<x-slot name="content">`タグ内にコンテンツとなるコードを書き込んでいきます。(2-14:30)

```diff
<x-slot name="content">
+    <div class="my-4">
+        <x-jet-label for="title" value="{{ __('Title') }}" />
+        <x-jet-input id="title" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="title" />
+    </div>
+    <input wire:model="title">
+    <div class="my-4">
+        <x-jet-label for="slug" value="{{ __('Slug') }}" />
+        <x-jet-input id="slug" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="slug" />
+    </div>
+    <input wire:model="slug">
</x-slot>
```

そして、コンテンツ部分のテキストエリアを加えると以下のようになります。

```html
<x-slot name="content">
    <div class="my-4">
        <x-jet-label for="title" value="{{ __('Title') }}" />
        <x-jet-input
            id="title"
            class="block mt-1 w-full"
            type="text"
            wire:model.debounce.800ms="title"
        />
    </div>
    <input wire:model="title" />

    <div class="my-4">
        <x-jet-label for="slug" value="{{ __('Slug') }}" />
        <div class="mt-1 flex rounded-md shadow-sm">
            <span
                class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"
            >
                http://laravel12.localhost/
            </span>
            <input
                type="text"
                id="slug"
                wire:model.debounce.800ms="slug"
                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md"
            />
        </div>
    </div>
    <input wire:model="slug" />

    <div class="my-4">
        <x-jet-label for="content" value="{{ __('Content') }}" />
        <textarea
            wire:model.debounce.800ms="content"
            id="content"
            rows="3"
            class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md "
        ></textarea>
    </div>
    <input wire:model="content" />
</x-slot>
```

`app\Http\Livewire\Pages.php` の編集

```diff
<?php

namespace App\Http\Livewire;

use Livewire\Component;
+use App\Models\Page;

class Pages extends Component
{

    public $modalFormVisible = false;
    public $slug;
    public $title;
    public $content;

+    public function create()
+    {
+        Page::create($this->modelData());
+        $this->modalFormVisible = false;
+    }

    public function createShowModal()
    {
        $this->modalFormVisible = true;
    }

+    public function modelData()
+    {
+        return [
+            'title' => $this->title,
+            'slug' => $this->slug,
+            'content' => $this->content,
+        ];
+    }

    public function render()
    {
        return view('livewire.pages');
    }
}

```

CREATE ボタンをクリックすると、新しいデータを記入するモダールウィンドウが表示されます。必要な情報を入力したら SAVE ボタンをクリックするとデータベースに保存されます。しかし、データを保存した後に、もう一度 CREATE ボタンをクリックすると、以前入力したデータがそのまま残っています。

なので、次に、この残ったデータを空にするメソッドを書いていきます。

`app\Http\Livewire\Pages.php` の編集

```diff
    public function create()
    {
        Page::create($this->modelData());
        $this->modalFormVisible = false;

+      $this->resetVars();
    }

+  public function resetVars()
+  {
+      $this->title = null;
+      $this->slug = null;
+      $this->content = null;
+  }
```

create メソッドに追加の処理を書いて、そして、reseetVars メソッドを新しく追加します。
一度、表示の確認を行ってみましょう。CREATE ボタンをクリックして、情報を入力したあとにＳＡＶＥボタンを押します。
その後に、もう一度、ＣＲＡＴＥボタンをクリックすると、さきほど入力した情報がクリアされているのが分かると思います。

次に、バリデーションの設定をします。

`app\Http\Livewire\Pages.php` の編集

```diff
public function create()
{
+ $this->validate();
    Page::create($this->modelData());
    $this->modalFormVisible = false;

    $this->resetVars();
}

+ public function rules()
+ {
+     return [
+         'title' => 'required',
+         'slug' => ['required', Rule::unique('pages', 'slug')],
+         'content' => 'required',
+     ];
+ }
```

`resources\views\livewire\pages.blade.php` の編集

```diff
        <x-slot name="content">
            <div class="my-4">
                <x-jet-label for="title" value="{{ __('Title') }}" />
                <x-jet-input id="title" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="title" />
+                @error('title')
+                <span class="error">{{ $message }}</span>
+                @enderror
            </div>
            <input wire:model="title">

            <div class="my-4">
                <x-jet-label for="slug" value="{{ __('Slug') }}" />
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        http://laravel12.localhost/
                    </span>
                    <input type="text" id="slug" wire:model.debounce.800ms="slug"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md">
                </div>
+                @error('slug')
+                <span class="error">{{ $message }}</span>
+                @enderror
            </div>
            <input wire:model="slug">

            <div class="my-4">
                <x-jet-label for="content" value="{{ __('Content') }}" />
                <textarea wire:model.debounce.800ms="content" id="content" rows="3"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md "></textarea>
+                @error('content')
+                <span class="error">{{ $message }}</span>
+                @enderror
            </div>
            <input wire:model="content">
        </x-slot>

```

次に Slug の対応した title にするため、`app\Http\Livewire\Pages.php` の編集を行います。

```diff
+    public function updatedTitle($value)
+    {
+        $this->generateSlug($value);
+    }
+
+    private function generateSlug($value)
+    {
+       $process1 = str_replace(' ', '-', $value);
+        $process2 = strtolower($process1);
+       $this->slug = $process2;
+   }
```