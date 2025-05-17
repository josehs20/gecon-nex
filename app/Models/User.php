<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Mercado\Entities\Usuario;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $connection = 'gecon';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'login',
        'email',
        'password',
        'modulo_id',
        // 'permite_abrir_caixa',
        'tipo_usuario_id',
        'empresa_id',
        'loja_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function isAdmin()
    {
        return $this->tipo_usuario_id == config('config.tipo_usuarios.admin.id');
    }

    public function isUsuarioMaster()
    {
        return $this->tipo_usuario_id == config('config.tipo_usuarios.cliente_master.id');
    }

    public function usuarioMercado()
    {
        return $this->hasOne(Usuario::class, 'usuario_master_cod');
    }

    public function usuarioGecon()
    {
        return $this->hasOne(User::class, 'id', 'id');
    }

    public function isCaixa()
    {
        return $this->permite_abrir_caixa == true;
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function getUserModulo()
    {
        switch ($this->modulo_id) {
            case config('config.modulos.gecon'):
                return $this->usuarioGecon();
                break;
            case config('config.modulos.mercado'):
                return $this->usuarioMercado();
                break;

            default:
                $this;
                break;
        }
    }

    public function modulo(){
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'tipo_usuario_id');
    }

    public function isUsuarioGecon(){
        $tipo_usuarios = config('config.tipo_usuarios');
        $usuarioTipoId = auth()->user()->tipo_usuario_id;

        foreach ($tipo_usuarios as $tipo) {
            if ($tipo['id'] === $usuarioTipoId) {
                return true;
            }
        }

        return false;
    }
}
