import {
  Datagrid,
  List,
  TextField,
  TextInput,
} from 'react-admin';


const postFilters = [
    <TextInput source="q" label="Search"  />,
];

// @ts-ignore
export const ProjectList = () => (
    <List filters={postFilters}>
        <Datagrid rowClick="edit">
            <TextField source="id" />
            <TextField source="name" />
        </Datagrid>
    </List>
);
