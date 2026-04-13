import applications from './applications'
import projects from './projects'
import requisitions from './requisitions'
const intermediate = {

    applications: Object.assign(applications, applications),
    projects: Object.assign(projects, projects),
    requisitions: Object.assign(requisitions, requisitions),

}

export default intermediate