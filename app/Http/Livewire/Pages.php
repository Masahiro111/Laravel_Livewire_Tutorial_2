<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Page;
use Illuminate\Validation\Rule;

class Pages extends Component
{

    public $modalFormVisible = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;

    /**
     * The validation rules
     *
     * @return void
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'slug' => ['required', Rule::unique('pages', 'slug')],
            'content' => 'required',
        ];
    }

    /**
     * Runs everytime the title
     * variable is updated.
     *
     * @param  mixed $value
     * @return void
     */
    public function updatedTitle($value)
    {
        $this->generateSlug($value);
    }

    public function create()
    {
        $this->validate();
        Page::create($this->modelData());
        $this->modalFormVisible = false;

        $this->resetVars();
    }

    /**
     * read
     *
     * @return void
     */
    public function read()
    {
        return Page::paginate(5);
    }

    public function createShowModal()
    {
        $this->resetVars();
        $this->modalFormVisible = true;
    }

    public function updateShowModal($id)
    {
        $this->resetVars();
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
    }

    /**
     * モデルデータを読み込みます。
     * 対象のmodelIdから、目的のデータを探します。
     * データを取得したら、livewireの変数に入れ込みます。
     *
     * @return void
     */
    public function loadModel()
    {
        $data = Page::find($this->modelId);
        // dd($data);
        $this->title = $data->title;
        $this->slug = $data->slug;
        $this->content = $data->content;
    }

    public function modelData()
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
        ];
    }

    public function resetVars()
    {
        $this->modelId = null;
        $this->title = null;
        $this->slug = null;
        $this->content = null;
    }

    /**
     * 
     * Generates a url generateSlug
     * base on the title.
     *
     * @param  mixed $value
     * @return void
     */
    private function generateSlug($value)
    {
        $process1 = str_replace(' ', '-', $value);
        $process2 = strtolower($process1);
        $this->slug = $process2;
    }

    public function render()
    {
        return view('livewire.pages', [
            'data' => $this->read(),
        ]);
    }
}
