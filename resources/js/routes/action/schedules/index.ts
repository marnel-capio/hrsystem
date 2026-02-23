import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:14
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
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules'
*/
index.url = (options?: RouteQueryOptions) => {




    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:14
* @route '/action/schedules'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
* @see app/Http/Controllers/ResourceScheduleController.php:14
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



const schedules = {
    index: Object.assign(index, index),
}

export default schedules