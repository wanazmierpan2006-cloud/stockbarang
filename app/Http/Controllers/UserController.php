<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        if (! $user) {
            return redirect()->route('users.index')->with('error', 'User tidak ditemukan.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, $id)
    {
        $this->userService->updateUser($id, $request->validated());

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }
        try {
            $this->userService->deleteUser($id);
        } catch (QueryException $e) {
            report($e);

            return redirect()->route('users.index')->with('error', 'User tidak dapat dihapus karena masih memiliki riwayat transaksi terkait.');
        }

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
