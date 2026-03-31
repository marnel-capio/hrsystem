import action from './action'
const namespaced = {
    action: Object.assign(action, action),
}

export default namespaced