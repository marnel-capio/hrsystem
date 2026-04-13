import projects from './projects'
import requisitions from './requisitions'
const intermediate = {
    projects: Object.assign(projects, projects),
requisitions: Object.assign(requisitions, requisitions),
}

export default intermediate