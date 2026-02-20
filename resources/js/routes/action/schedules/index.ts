import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:0
* @route '/action/schedules'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/schedules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:0
* @route '/action/schedules'
*/
index.url = (options?: RouteQueryOptions) => {




    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:0
* @route '/action/schedules'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:0
* @route '/action/schedules'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:0
* @route '/action/schedules'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:0
* @route '/action/schedules'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:0
* @route '/action/schedules'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\ResourceScheduleController::create
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/schedules/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::create
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules/create'
*/
create.url = (options?: RouteQueryOptions) => {




    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ResourceScheduleController::create
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::create
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::create
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::create
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::create
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules/create'
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
* @see \App\Http\Controllers\ResourceScheduleController::store
* @see app/Http/Controllers/ResourceScheduleController.php:24
* @route '/action/schedules'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/action/schedules',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::store
* @see app/Http/Controllers/ResourceScheduleController.php:24
* @route '/action/schedules'
*/
store.url = (options?: RouteQueryOptions) => {




    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ResourceScheduleController::store
* @see app/Http/Controllers/ResourceScheduleController.php:24
* @route '/action/schedules'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::store
* @see app/Http/Controllers/ResourceScheduleController.php:24
* @route '/action/schedules'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::store
* @see app/Http/Controllers/ResourceScheduleController.php:24
* @route '/action/schedules'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\ResourceScheduleController::show
* @see app/Http/Controllers/ResourceScheduleController.php:36
* @route '/action/schedules/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/action/schedules/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::show
* @see app/Http/Controllers/ResourceScheduleController.php:36
* @route '/action/schedules/{id}'
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
* @see \App\Http\Controllers\ResourceScheduleController::show
* @see app/Http/Controllers/ResourceScheduleController.php:36
* @route '/action/schedules/{id}'
*/
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::show
* @see app/Http/Controllers/ResourceScheduleController.php:36
* @route '/action/schedules/{id}'
*/
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::show
* @see app/Http/Controllers/ResourceScheduleController.php:36
* @route '/action/schedules/{id}'
*/
const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::show
* @see app/Http/Controllers/ResourceScheduleController.php:36
* @route '/action/schedules/{id}'
*/
showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::show
* @see app/Http/Controllers/ResourceScheduleController.php:36
* @route '/action/schedules/{id}'
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



const schedules = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
}

export default schedules