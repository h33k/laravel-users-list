<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    #[Url(history:true)]
    public $search = '';
    #[Url()]
    public $perPage = 10;
    #[Url(history:true)]
    public $admin = '';
    #[Url(history:true)]
    public $sortBy = 'created_at';
    #[Url(history:true)]
    public $sortDirection = 'asc';

    public function delete(User $user)
    {
        $user->delete();
    }

    public function updatedSearch() {
        $this->resetPage();
    }

    public function setSortBy($sortByField)
    {
        if($this->sortBy === $sortByField) {
            $this->sortDirection = ($this->sortDirection == 'asc') ? 'desc' : 'asc';
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        return view('livewire.users-table',
        [
            'users' => User::search($this->search)
                ->when($this->admin !== '', function($query) {
                    $query->where('is_admin', $this->admin);
                })
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage)
        ]
        );
    }
}
