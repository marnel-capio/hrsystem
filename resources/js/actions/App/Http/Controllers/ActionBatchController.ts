import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches'
*/
const index6a7767732185e5ad4936faa232bd89ab = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index6a7767732185e5ad4936faa232bd89ab.url(options),
    method: 'get',
})

index6a7767732185e5ad4936faa232bd89ab.definition = {
    methods: ["get","head"],
    url: '/action/batches',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches'
*/
index6a7767732185e5ad4936faa232bd89ab.url = (options?: RouteQueryOptions) => {




    return index6a7767732185e5ad4936faa232bd89ab.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches'
*/
index6a7767732185e5ad4936faa232bd89ab.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index6a7767732185e5ad4936faa232bd89ab.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches'
*/
index6a7767732185e5ad4936faa232bd89ab.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index6a7767732185e5ad4936faa232bd89ab.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches'
*/
const index6a7767732185e5ad4936faa232bd89abForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index6a7767732185e5ad4936faa232bd89ab.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches'
*/
index6a7767732185e5ad4936faa232bd89abForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index6a7767732185e5ad4936faa232bd89ab.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches'
*/
index6a7767732185e5ad4936faa232bd89abForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index6a7767732185e5ad4936faa232bd89ab.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index6a7767732185e5ad4936faa232bd89ab.form = index6a7767732185e5ad4936faa232bd89abForm
/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches/create'
*/
const indexb348c80b12761851da58903f661b5d50 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexb348c80b12761851da58903f661b5d50.url(options),
    method: 'get',
})

indexb348c80b12761851da58903f661b5d50.definition = {
    methods: ["get","head"],
    url: '/action/batches/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches/create'
*/
indexb348c80b12761851da58903f661b5d50.url = (options?: RouteQueryOptions) => {




    return indexb348c80b12761851da58903f661b5d50.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches/create'
*/
indexb348c80b12761851da58903f661b5d50.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexb348c80b12761851da58903f661b5d50.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches/create'
*/
indexb348c80b12761851da58903f661b5d50.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexb348c80b12761851da58903f661b5d50.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches/create'
*/
const indexb348c80b12761851da58903f661b5d50Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexb348c80b12761851da58903f661b5d50.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches/create'
*/
indexb348c80b12761851da58903f661b5d50Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexb348c80b12761851da58903f661b5d50.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:11
* @route '/action/batches/create'
*/
indexb348c80b12761851da58903f661b5d50Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexb348c80b12761851da58903f661b5d50.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexb348c80b12761851da58903f661b5d50.form = indexb348c80b12761851da58903f661b5d50Form

export const index = {
    '/action/batches': index6a7767732185e5ad4936faa232bd89ab,
    '/action/batches/create': indexb348c80b12761851da58903f661b5d50,
}


const ActionBatchController = { index }

export default ActionBatchController