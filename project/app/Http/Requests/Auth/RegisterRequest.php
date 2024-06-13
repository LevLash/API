<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Services\RobotsService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => 'required|confirmed|min:8',
        ];
    }

    /**
     * @throws ValidationException
     */
    protected function passedValidation()
    {
        if (((new RobotsService())->register($this->validated()) === null)) {
            throw ValidationException::withMessages([
                'email' => 'API server return error',
            ]);
        }
    }
}
