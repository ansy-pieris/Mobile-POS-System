<?php

namespace App\Livewire;

use App\Models\Settings;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShopSettings extends Component
{
    use WithFileUploads;

    public $shop_name = '';
    public $address = '';
    public $location = '';
    public $phone = '';
    public $email = '';
    public $logo;
    public $existing_logo = '';
    public $terms_conditions = '';

    protected $rules = [
        'shop_name' => 'required|string|max:255',
        'address' => 'nullable|string|max:500',
        'location' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'logo' => 'nullable|image|max:2048', // 2MB max
        'terms_conditions' => 'nullable|string|max:5000',
    ];

    public function mount()
    {
        $settings = Settings::first();
        if ($settings) {
            $this->shop_name = $settings->shop_name;
            $this->address = $settings->address;
            $this->location = $settings->location ?? '';
            $this->phone = $settings->phone;
            $this->email = $settings->email;
            $this->existing_logo = $settings->logo;
            $this->terms_conditions = $settings->terms_conditions ?? '';
        }
    }

    public function render()
    {
        return view('livewire.shop-settings')->layout('layouts.app');
    }

    public function save()
    {
        $this->validate();

        $data = [
            'shop_name' => $this->shop_name,
            'address' => $this->address,
            'location' => $this->location,
            'phone' => $this->phone,
            'email' => $this->email,
            'terms_conditions' => $this->terms_conditions,
        ];

        // Handle logo upload
        if ($this->logo) {
            $logoPath = $this->logo->store('logos', 'public');
            $data['logo'] = $logoPath;
            $this->existing_logo = $logoPath;
        }

        $settings = Settings::first();
        if ($settings) {
            $settings->update($data);
        } else {
            Settings::create($data);
        }

        $this->logo = null;
        session()->flash('message', 'Shop details saved successfully.');
    }

    public function removeLogo()
    {
        $settings = Settings::first();
        if ($settings && $settings->logo) {
            // Delete old logo file
            $oldPath = storage_path('app/public/' . $settings->logo);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $settings->update(['logo' => null]);
            $this->existing_logo = '';
            session()->flash('message', 'Logo removed successfully.');
        }
    }
}
