<?php

namespace App\Services;

use App\Models\StandarMutu;

class StandarTreeService
{
    public function getTree()
    {
        $standar = StandarMutu::with('isiStandar')->get();

        foreach ($standar as $item) {

            $item->tree = $this->buildTree(
                $item->isiStandar
            );

        }

        return $standar;
    }

    private function buildTree($collection, $parent = null)
    {
        return $collection
            ->where('parent_standar_id', $parent)
            ->map(function ($node) use ($collection) {

                $node->children = $this->buildTree(
                    $collection,
                    $node->id
                );

                return $node;

            })
            ->values();
    }
}