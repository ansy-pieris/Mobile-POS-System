<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    public $name = '';
    public $description = '';
    public $categoryId = null;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
    ];

    public function render()
    {
        return view('livewire.categories', [
            'categories' => Category::latest()->paginate(10)
        ])->layout('layouts.app');
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $category = Category::findOrFail($this->categoryId);
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Category updated successfully.');
        } else {
            Category::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Category created successfully.');
        }

        $this->reset(['name', 'description', 'categoryId', 'isEditing']);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted successfully.');
    }

    public function cancel()
    {
        $this->reset(['name', 'description', 'categoryId', 'isEditing']);
    }
}
