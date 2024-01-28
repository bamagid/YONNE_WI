<?php

namespace App\Http\Controllers;

use App\Models\Reseau;
use App\Http\Requests\ReseauRequest;

class ReseauController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index', 'show');
    }

    public function index()
    {
        $reseaux = Reseau::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des reseaux actifs",
            "reseaux" => $reseaux
        ], 200);
    }

    public function store(ReseauRequest $request)
    {
        $reseau = Reseau::create($request->validated());
        return response()->json([
            "message" => "Le reseau a bien été enregistré",
            "reseau" => $reseau
        ], 201);
    }

    public function show(Reseau $reseau)
    {
        if ($reseau->etat == "supprimé") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Reseau] $reseau->id"
            ], 404);
        }
        return response()->json([
            "message" => "Voici le reseau que vous recherchez",
            "reseau" => $reseau
        ], 200);
    }

    public function update(ReseauRequest $request, Reseau $reseau)
    {
        $reseau->update($request->validated());
        return response()->json([
            "message" => "Le reseau a bien été mise à jour",
            "reseau" => $reseau
        ], 200);
    }

    public function destroy(Reseau $reseau)
    {
        $reseau->update(['etat' => 'corbeille']);
        return response()->json([
            "message" => "Le reseau a bien été mis dans la corbeille",
            "reseau" => $reseau
        ]);
    }
    public function delete(Reseau $reseau)
    {
        if ($reseau->etat === "corbeille") {
            $reseau->update(['etat' => 'supprimé']);
            return response()->json([
                "message" => "Le reseau a bien été supprimé",
                "reseau" => $reseau
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvez pas supprimé un element qui n'est pas dans la corbeille",
        ], 422);
    }

    public function restore(Reseau $reseau)
    {
        $reseau->update(['etat' => 'actif']);
        return response()->json([
            "message" => "Le reseau a bien été restauré",
            "reseau" => $reseau
        ]);
    }

    public function deleted()
    {
        $reseauxSupprimes = Reseau::where('etat', 'corbeille')->get();
        if (empty($reseauxSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de réseaux supprimés"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des reseaux quui son dans la corbeilles",
            "reseaux" => $reseauxSupprimes
        ], 200);
    }

    public function emptyTrash()
    {
        $reseausSupprimes = Reseau::where('etat', 'corbeille')->get();
        if (empty($reseausSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de réseaux supprimés"
            ], 404);
        }
        foreach ($reseausSupprimes as $reseau) {
            $reseau->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
