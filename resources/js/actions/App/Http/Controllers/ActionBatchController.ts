import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionBatchController::index
<<<<<<< HEAD
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches'
*/
=======
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/batches',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::index
<<<<<<< HEAD
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches'
*/
index.url = (options?: RouteQueryOptions) => {




=======
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
index.url = (options?: RouteQueryOptions) => {
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::index
<<<<<<< HEAD
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches'
*/
=======
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
<<<<<<< HEAD
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches'
*/
=======
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
<<<<<<< HEAD
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::index
* @see app/Http/Controllers/ActionBatchController.php:12
* @route '/action/batches'
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
* @see \App\Http\Controllers\ActionBatchController::create
* @see app/Http/Controllers/ActionBatchController.php:39
* @route '/action/batches/register'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

=======
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
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
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:39
 * @route '/action/batches/register'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
create.definition = {
    methods: ["get","head"],
    url: '/action/batches/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::create
<<<<<<< HEAD
* @see app/Http/Controllers/ActionBatchController.php:39
* @route '/action/batches/register'
*/
create.url = (options?: RouteQueryOptions) => {




=======
 * @see app/Http/Controllers/ActionBatchController.php:39
 * @route '/action/batches/register'
 */
create.url = (options?: RouteQueryOptions) => {
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::create
<<<<<<< HEAD
* @see app/Http/Controllers/ActionBatchController.php:39
* @route '/action/batches/register'
*/
=======
 * @see app/Http/Controllers/ActionBatchController.php:39
 * @route '/action/batches/register'
 */
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::create
<<<<<<< HEAD
* @see app/Http/Controllers/ActionBatchController.php:39
* @route '/action/batches/register'
*/
=======
 * @see app/Http/Controllers/ActionBatchController.php:39
 * @route '/action/batches/register'
 */
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

<<<<<<< HEAD
/**
* @see \App\Http\Controllers\ActionBatchController::create
* @see app/Http/Controllers/ActionBatchController.php:39
* @route '/action/batches/register'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::create
* @see app/Http/Controllers/ActionBatchController.php:39
* @route '/action/batches/register'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::create
* @see app/Http/Controllers/ActionBatchController.php:39
* @route '/action/batches/register'
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
* @see app/Http/Controllers/ActionBatchController.php:43
* @route '/action/batches/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

=======
    /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:39
 * @route '/action/batches/register'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:39
 * @route '/action/batches/register'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:39
 * @route '/action/batches/register'
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
 * @see app/Http/Controllers/ActionBatchController.php:43
 * @route '/action/batches/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
show.definition = {
    methods: ["get","head"],
    url: '/action/batches/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::show
<<<<<<< HEAD
* @see app/Http/Controllers/ActionBatchController.php:43
* @route '/action/batches/{id}'
*/
=======
 * @see app/Http/Controllers/ActionBatchController.php:43
 * @route '/action/batches/{id}'
 */
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
show.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

<<<<<<< HEAD

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
=======
    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
    }

    args = applyUrlDefaults(args)

<<<<<<< HEAD

    const parsedArgs = {
        id: args.id,
    }
=======
    const parsedArgs = {
                        id: args.id,
                }
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac

    return show.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::show
<<<<<<< HEAD
* @see app/Http/Controllers/ActionBatchController.php:43
* @route '/action/batches/{id}'
*/
=======
 * @see app/Http/Controllers/ActionBatchController.php:43
 * @route '/action/batches/{id}'
 */
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
<<<<<<< HEAD

/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:43
* @route '/action/batches/{id}'
*/
=======
/**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:43
 * @route '/action/batches/{id}'
 */
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

<<<<<<< HEAD
/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:43
* @route '/action/batches/{id}'
*/
const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:43
* @route '/action/batches/{id}'
*/
showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActionBatchController::show
* @see app/Http/Controllers/ActionBatchController.php:43
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

=======
    /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:43
 * @route '/action/batches/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:43
 * @route '/action/batches/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:43
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
>>>>>>> 45a2c07bd1b0bbe478e6229c58e585e564a051ac
const ActionBatchController = { index, create, show }

export default ActionBatchController