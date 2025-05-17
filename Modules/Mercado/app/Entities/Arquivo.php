<?php

namespace Modules\Mercado\Entities;

class Arquivo extends ModelBase
{
    protected $table = 'arquivos';
    protected $connection = 'mercado';

        /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_arquivo_id',
        'path',
        'loja_id',
    ];

    /**
     * Define a relação com o model TipoArquivo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoArquivo()
    {
        return $this->belongsTo(TipoArquivo::class, 'tipo_arquivo_id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }
}