<?php


namespace Crmdesenvolvimentos\PixSicredi\Resources\Traits;

use Carbon\Carbon;

trait BasicFilter
{

    public array $filters = [];


    public function __construct()
    {
        $this->filters['inicio'] = Carbon::now()->subMonth()->toIso8601String();

        $this->filters['fim'] = Carbon::now()->toIso8601String();
    }


    public function dataInicial(string $start): self
    {
        $this->filters['inicio'] = Carbon::parse($start)->toIso8601String();

        return $this;
    }


    public function dataFinal(string $end): self
    {
        $this->filters['fim'] = Carbon::parse($end)->toIso8601String();

        return $this;
    }


    public function itemsPorPagina(int $itemsPorPagina): self
    {
        $this->filters['paginacao.itensPorPagina'] = $itemsPorPagina;

        return $this;
    }


    public function paginaAtual(int $paginaAtual): self
    {
        $this->filters['paginacao.paginaAtual'] = $paginaAtual;

        return $this;
    }


    public function toArray(): array
    {
        return ['query' => $this->filters];
    }

}
