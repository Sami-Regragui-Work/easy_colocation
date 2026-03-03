<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class InvitationAuthRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
            '_action' => 'required|in:login,register'
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('name', 'required|string|min:2|max:255', function ($input) {
            return $input['_action'] === 'register';
        });

        $validator->sometimes('password_confirmation', 'required|same:password', function ($input) {
            return $input['_action'] === 'register';
        });

        $validator->after(function ($validator) {
            $data = $validator->getData();
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;
            $action = $data['_action'] ?? null;

            if (!$email) {
                return;
            }

            $user = User::where('email', $email)->whereNull('banned_at')->first();

            if ($action === 'login') {
                if (!$user || !Hash::check($password, $user?->password ?? '')) {
                    $validator->errors()->add('password', 'Invalid credentials.');
                }
            } elseif ($action === 'register') {
                if ($user) {
                    $validator->errors()->add('email', 'An account already exists for this email.');
                }
            }
        });
    }
}
