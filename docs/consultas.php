<?php 
	$sql = \DB::table('users')
            ->whereExists(function ($query) {
                $query->select(\DB::raw(1))
                      ->from('perfil')
                      ->whereRaw('users.perfil_id = perfil.id');
            })
            ->get();

        $role = 'Master';

    $sql = \DB::table('perfil')
                    ->where('id', '!=', NULL)
                    ->where(function($query) use ($role){
                        if ($role == 'Master') {
                            $query->where('rol', '=', $role);
                        }
                    })
                    ->get();

    $estado = Recurso::ACTIVO;
    $sql = \DB::table('recurso')
                ->where('id', '!=', NULL)
                ->where(function($query) use ($estado){
                    if ($estado != 'todos') {
                        if ($estado==Recurso::ACTIVO) {
                            $query->where('activo','=',Recurso::ACTIVO);
                        } else {
                            $query->where('activo','=',Recurso::INACTIVO);
                        }
                        //($estado==Recurso::ACTIVO) ? $query->where('activo','=',Recurso::ACTIVO) : $query->where('activo','=',Recurso::INACTIVO);
                    }
                })
                ->orderBy('recurso', 'ASC')
                ->pluck('recurso', 'id')->toArray();




    /*Depois disso, muitos de nós (não esses caras geeky que escrevem alguns serviços e etc. para gerenciar este caso) apenas façam algo assim em nossos controladores:*/
	$query = Product::newInstance();

	if ($request->color) {
	$query->whereColor($request->color);
	}

	if ($request->size) {
	$query->whereSize($request->size);
	}

	if ($request->orderBy && $request->sort) {
	$query->orderby($request->orderBy, $request->sort);
	}

	$products = $query->get();

	/*Desta forma, é bastante comum. Mais uma coisa, muitas vezes você quer ter pedidos padrão, então você precisa adicionar uma afirmação "else":*/
	$query = Product::newInstance();
	 
	if ($request->color) {
	    $query->whereColor($request->color);
	}
	 
	if ($request->size) {
	    $query->whereSize($request->size);
	}
	 
	if ($request->orderBy && $request->sort) {
	    $query->orderby($request->orderBy, $request->sort);
	} else {
	    $query->orderby('price', 'desc');
	    // or how I like $query->latest('price'); it's the same, just easier to remember
	}
 
	$products = $query->get();

	/*Mas quando você começa a usar cláusulas condicionais :*/
	$products = Product::when($request->color, function ($query) use ($request) {
	    return $query->whereColor($request->color);
	})
	->when($request->size, function ($query) use ($request) {
	   return $query->whereSize($request->size);
	})
	->when($request->orderBy && $request->sort, function ($query) use ($request) {
	   return $query->orderBy($request->orderBy, $request->sort);
	})
	->get();

	/*Muito mais fácil, sim? Aguarde, mas e quanto ao pedido padrão? Você pode passá-lo como terceiro parâmetro no encerramento:*/
	$products = Product::when($request->color, function ($query) use ($request) {
	    return $query->whereColor($request->color);
	})
	->when($request->size, function ($query) use ($request) {
	   return $query->whereSize($request->size);
	})
	->when($request->orderBy && $request->sort, function ($query) use ($request) {
	   return $query->orderBy($request->orderBy, $request->sort);
	}, function ($query) {
	   return $query->latest('price');
	})
	->get();


	/**
	 * Outor modelo com $request
	 */
	$type = $request->input('email_or_phone');

	$user = UserVerify::where('user_id', $request->input('user_id'))
	                    ->where(function($query) use ($type, $request) {
	                        if ($type == 1) {
	                            return $query->where('email', $request->input('email'));
	                        }
	                        if ($type == 2) {
	                            return $query->where('mobile_no', $request->input('mobile_no'));
	                        }
	                    })
	                    ->where('email_or_phone', $request->input('email_or_phone'))
	                    ->where('pin_code', $request->input('pin_code'))
	                    ->first();
	?>