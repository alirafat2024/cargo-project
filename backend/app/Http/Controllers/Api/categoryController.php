<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /api/categories
    public function index(Request $request)
    {
        $categories = Category::all();
        return response()->json(['status' => 'success', 'data' => $categories]);
    }

    // POST /api/categories (admin only)
    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
            'address' => 'required|string',
            'description' => 'required|string',
        ]);

        $category = Category::create($validated);

        return response()->json(['status' => 'success', 'data' => $category], 201);
    }

    // GET /api/categories/{id}
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $category]);
    }

    // PUT/PATCH /api/categories/{id} (admin only)
    public function update(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:categories,slug,' . $category->id,
            'address' => 'sometimes|string',
            'description' => 'sometimes|string',
        ]);

        $category->update($validated);

        return response()->json(['status' => 'success', 'data' => $category]);
    }

    // DELETE /api/categories/{id} (soft delete, admin only)
    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['status' => 'success', 'message' => 'Category trashed']);
    }

    // GET /api/categories/trash (admin only)
    public function trash(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $trashed = Category::onlyTrashed()->get();
        return response()->json(['status' => 'success', 'data' => $trashed]);
    }

    // POST /api/categories/{id}/restore (admin only)
    public function restore(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return response()->json(['status' => 'success', 'message' => 'Category restored', 'data' => $category]);
    }

    // DELETE /api/categories/{id}/force (admin only)
    public function forceDelete(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        return response()->json(['status' => 'success', 'message' => 'Category permanently deleted']);
    }
}
