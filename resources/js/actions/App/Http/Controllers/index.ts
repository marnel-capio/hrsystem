import UserController from './UserController'
import ActionBatchController from './ActionBatchController'
import Settings from './Settings'
const Controllers = {
    UserController: Object.assign(UserController, UserController),
ActionBatchController: Object.assign(ActionBatchController, ActionBatchController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers