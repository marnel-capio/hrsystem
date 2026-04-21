import projects from './projects'
import requisitions from './requisitions'
import applications from './applications'
const intermediate = {
    projects: Object.assign(projects, projects),
requisitions: Object.assign(requisitions, requisitions),
applications: Object.assign(applications, applications),
}

export default intermediate