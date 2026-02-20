import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:15
* @route '/action/schedules/register'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/schedules/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:15
* @route '/action/schedules/register'
*/
create.url = (options?: RouteQueryOptions) => {




    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:15
* @route '/action/schedules/register'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:15
* @route '/action/schedules/register'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:15
* @route '/action/schedules/register'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:15
* @route '/action/schedules/register'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:15
* @route '/action/schedules/register'
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
* @see \App\Http\Controllers\Settings\ResourceScheduleController::store
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:25
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
* @see \App\Http\Controllers\Settings\ResourceScheduleController::store
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:25
* @route '/action/schedules'
*/
store.url = (options?: RouteQueryOptions) => {




    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::store
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:25
* @route '/action/schedules'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::store
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:25
* @route '/action/schedules'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::store
* @see app/Http/Controllers/Settings/ResourceScheduleController.php:25
* @route '/action/schedules'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm



const schedules = {
    create: Object.assign(create, create),
    store: Object.assign(store, store),
}

export default schedules