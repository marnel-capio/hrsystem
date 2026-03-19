import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:22
 * @route '/action/applicants'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/applicants',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:22
 * @route '/action/applicants'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:22
 * @route '/action/applicants'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:22
 * @route '/action/applicants'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:22
 * @route '/action/applicants'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:22
 * @route '/action/applicants'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:22
 * @route '/action/applicants'
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
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:13
 * @route '/action/applicants/register'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/applicants/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:13
 * @route '/action/applicants/register'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:13
 * @route '/action/applicants/register'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:13
 * @route '/action/applicants/register'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:13
 * @route '/action/applicants/register'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:13
 * @route '/action/applicants/register'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:13
 * @route '/action/applicants/register'
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
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:27
 * @route '/action/applicants'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/action/applicants',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:27
 * @route '/action/applicants'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:27
 * @route '/action/applicants'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:27
 * @route '/action/applicants'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:27
 * @route '/action/applicants'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const ActionApplicantController = { index, create, store }

export default ActionApplicantController