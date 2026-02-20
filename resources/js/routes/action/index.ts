import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\ActionBatchController::list
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches/list'
*/
export const list = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

list.definition = {
    methods: ["get","head"],
    url: '/action/batches/list',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::list
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches/list'
*/
list.url = (options?: RouteQueryOptions) => {




    return list.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::list
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches/list'
*/
list.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::list
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches/list'
*/
list.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: list.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ActionBatchController::list
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches/list'
*/
const listForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: list.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::list
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches/list'
*/
listForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: list.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::list
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches/list'
*/
listForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: list.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

list.form = listForm

/**
* @see routes/web.php:25
* @route '/action/batches/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/batches/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:25
* @route '/action/batches/create'
*/
create.url = (options?: RouteQueryOptions) => {




    return create.definition.url + queryParams(options)
}

/**
* @see routes/web.php:25
* @route '/action/batches/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see routes/web.php:25
* @route '/action/batches/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see routes/web.php:25
* @route '/action/batches/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see routes/web.php:25
* @route '/action/batches/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see routes/web.php:25
* @route '/action/batches/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:34
* @route '/action/batches/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/action/batches/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:34
* @route '/action/batches/{id}'
*/
show.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }


    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)


    const parsedArgs = {
        id: args.id,
    }

    return show.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:34
* @route '/action/batches/{id}'
*/
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:34
* @route '/action/batches/{id}'
*/
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:34
* @route '/action/batches/{id}'
*/
const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:34
* @route '/action/batches/{id}'
*/
showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:34
* @route '/action/batches/{id}'
*/
showForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\ActionBatchController::update
* @see app/Http/Controllers/ActionBatchController.php:0
* @route '/action/batches/{id}'
*/
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/action/batches/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\ActionBatchController::update
* @see app/Http/Controllers/ActionBatchController.php:0
* @route '/action/batches/{id}'
*/
update.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }


    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)


    const parsedArgs = {
        id: args.id,
    }

    return update.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::update
* @see app/Http/Controllers/ActionBatchController.php:0
* @route '/action/batches/{id}'
*/
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\ActionBatchController::update
* @see app/Http/Controllers/ActionBatchController.php:0
* @route '/action/batches/{id}'
*/
const updateForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ActionBatchController::update
* @see app/Http/Controllers/ActionBatchController.php:0
* @route '/action/batches/{id}'
*/
updateForm.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm



const action = {
    list: Object.assign(list, list),
    create: Object.assign(create, create),
    show: Object.assign(show, show),
    update: Object.assign(update, update),
}

export default action