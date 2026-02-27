import AuthController from './AuthController'
import ForgotPasswordController from './ForgotPasswordController'
import DashboardController from './DashboardController'
import UserController from './UserController'
import Settings from './Settings'
const Controllers = {
    AuthController: Object.assign(AuthController, AuthController),
ForgotPasswordController: Object.assign(ForgotPasswordController, ForgotPasswordController),
DashboardController: Object.assign(DashboardController, DashboardController),
UserController: Object.assign(UserController, UserController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers