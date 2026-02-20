import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/'
*/
const index980bb49ee7ae63891f1d891d2fbcf1c9 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

index980bb49ee7ae63891f1d891d2fbcf1c9.definition = {
    methods: ["get","head"],
    url: '/',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/'
*/
index980bb49ee7ae63891f1d891d2fbcf1c9.url = (options?: RouteQueryOptions) => {




    return index980bb49ee7ae63891f1d891d2fbcf1c9.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/'
*/
index980bb49ee7ae63891f1d891d2fbcf1c9.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/'
*/
index980bb49ee7ae63891f1d891d2fbcf1c9.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/'
*/
const index980bb49ee7ae63891f1d891d2fbcf1c9Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/'
*/
index980bb49ee7ae63891f1d891d2fbcf1c9Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/'
*/
index980bb49ee7ae63891f1d891d2fbcf1c9Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index980bb49ee7ae63891f1d891d2fbcf1c9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index980bb49ee7ae63891f1d891d2fbcf1c9.form = index980bb49ee7ae63891f1d891d2fbcf1c9Form
/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/dashboard'
*/
const index42a740574ecbfbac32f8cc353fc32db9 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'get',
})

index42a740574ecbfbac32f8cc353fc32db9.definition = {
    methods: ["get","head"],
    url: '/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/dashboard'
*/
index42a740574ecbfbac32f8cc353fc32db9.url = (options?: RouteQueryOptions) => {




    return index42a740574ecbfbac32f8cc353fc32db9.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/dashboard'
*/
index42a740574ecbfbac32f8cc353fc32db9.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/dashboard'
*/
index42a740574ecbfbac32f8cc353fc32db9.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/dashboard'
*/
const index42a740574ecbfbac32f8cc353fc32db9Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/dashboard'
*/
index42a740574ecbfbac32f8cc353fc32db9Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index42a740574ecbfbac32f8cc353fc32db9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:13
* @route '/dashboard'
*/
index42a740574ecbfbac32f8cc353fc32db9Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index42a740574ecbfbac32f8cc353fc32db9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index42a740574ecbfbac32f8cc353fc32db9.form = index42a740574ecbfbac32f8cc353fc32db9Form

export const index = {
    '/': index980bb49ee7ae63891f1d891d2fbcf1c9,
    '/dashboard': index42a740574ecbfbac32f8cc353fc32db9,
}


const DashboardController = { index }

export default DashboardController