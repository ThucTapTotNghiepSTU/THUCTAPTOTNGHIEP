<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LecturerController extends Controller
{
    /**
     * Lấy danh sách tất cả giảng viên
     */
    public function index()
    {
        return response()->json(Lecturer::all(), Response::HTTP_OK);
    }

    /**
     * Lấy chi tiết một giảng viên
     */
    public function show($id)
    {
        $lecturer = Lecturer::find($id);
        if (!$lecturer) {
            return response()->json(['message' => 'Giảng viên không tồn tại!'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($lecturer, Response::HTTP_OK);
    }

    /**
     * Thêm mới một giảng viên
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'school_email' => 'required|email|unique:lecturer,school_email',
            'personal_email' => 'nullable|email|unique:lecturer,personal_email',
            'phone' => 'required|string|max:20|unique:lecturer,phone',
            'password' => 'required|string|min:6',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $lecturer = Lecturer::create($validatedData);
        return response()->json(['message' => 'Thêm giảng viên thành công!', 'data' => $lecturer], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật thông tin giảng viên
     */
    public function update(Request $request, $id)
    {
        $lecturer = Lecturer::find($id);
        if (!$lecturer) {
            return response()->json(['message' => 'Giảng viên không tồn tại!'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'fullname' => 'string|max:255',
            'school_email' => 'email|unique:lecturer,school_email,' . $id . ',lecturer_id',
            'personal_email' => 'nullable|email|unique:lecturer,personal_email,' . $id . ',lecturer_id',
            'phone' => 'string|max:20|unique:lecturer,phone,' . $id . ',lecturer_id',
            'password' => 'nullable|string|min:6',
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $lecturer->update($validatedData);
        return response()->json(['message' => 'Cập nhật giảng viên thành công!', 'data' => $lecturer], Response::HTTP_OK);
    }

    /**
     * Xóa một giảng viên
     */
    public function destroy($id)
    {
        $lecturer = Lecturer::find($id);
        if (!$lecturer) {
            return response()->json(['message' => 'Giảng viên không tồn tại!'], Response::HTTP_NOT_FOUND);
        }

        $lecturer->delete();
        return response()->json(['message' => 'Xóa giảng viên thành công!'], Response::HTTP_OK);
    }

    public function getClassrooms($id)
    {
        $lecturer = Lecturer::with(['classrooms.course'])->find($id);
        if (!$lecturer) {
            return response()->json(['message' => 'Giảng viên không tồn tại!'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'lecturer_id' => $lecturer->lecturer_id,
            'fullname' => $lecturer->fullname,
            'classrooms' => $lecturer->classrooms
        ], Response::HTTP_OK);
    }

}
