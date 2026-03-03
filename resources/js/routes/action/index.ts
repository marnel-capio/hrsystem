import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import batches from './batches'
/**
 * @see routes/web.php:38
 * @route '/action'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web.php:38
 * @route '/action'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
 * @see routes/web.php:38
 * @route '/action'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
 * @see routes/web.php:38
 * @route '/action'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
 * @see routes/web.php:38
 * @route '/action'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
 * @see routes/web.php:38
 * @route '/action'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
 * @see routes/web.php:38
 * @route '/action'
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
 * @see routes/web.php:39
 * @route '/action/applications'
 */
export const applications = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: applications.url(options),
    method: 'get',
})

applications.definition = {
    methods: ["get","head"],
    url: '/action/applications',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web.php:39
 * @route '/action/applications'
 */
applications.url = (options?: RouteQueryOptions) => {
    return applications.definition.url + queryParams(options)
}

/**
 * @see routes/web.php:39
 * @route '/action/applications'
 */
applications.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: applications.url(options),
    method: 'get',
})
/**
 * @see routes/web.php:39
 * @route '/action/applications'
 */
applications.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: applications.url(options),
    method: 'head',
})

    /**
 * @see routes/web.php:39
 * @route '/action/applications'
 */
    const applicationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: applications.url(options),
        method: 'get',
    })

            /**
 * @see routes/web.php:39
 * @route '/action/applications'
 */
        applicationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: applications.url(options),
            method: 'get',
        })
            /**
 * @see routes/web.php:39
 * @route '/action/applications'
 */
        applicationsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: applications.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    applications.form = applicationsForm
const action = {
    index: Object.assign(index, index),
applications: Object.assign(applications, applications),
batches: Object.assign(batches, batches),
}

export default action