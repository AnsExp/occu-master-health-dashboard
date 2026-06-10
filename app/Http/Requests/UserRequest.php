<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
            'password' => ['required', 'string', 'min:6'],
            'password_confirmation' => ['required', 'string', 'min:6', 'same:password'],
        ];
        if ($this->input('role') === RoleEnum::DOCTOR->code()) {
            $rules['phone'] = ['required', 'string', 'max:255', Rule::unique('doctors', 'phone')];
            $rules['id_card'] = ['required', 'string', 'max:255', Rule::unique('doctors', 'id_card')];
            $rules['last_name'] = ['required', 'string', 'max:255'];
            $rules['specialty'] = ['required', 'string', Rule::exists('specialties', 'name')];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'role.required' => 'El rol es obligatorio.',
            'role.exists' => 'El rol seleccionado no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password_confirmation.required' => 'La confirmación de la contraseña es obligatoria.',
            'password_confirmation.same' => 'La confirmación de la contraseña no coincide.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.unique' => 'El teléfono ya está en uso.',
            'id_card.required' => 'La cédula es obligatoria.',
            'id_card.unique' => 'La cédula ya está en uso.',
            'last_name.required' => 'El apellido es obligatorio.',
            'specialty.required' => 'La especialidad es obligatoria.',
            'specialty.exists' => 'La especialidad seleccionada no es válida.',
        ];
    }
}
