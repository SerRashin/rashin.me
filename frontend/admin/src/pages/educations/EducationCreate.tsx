import {Create, required, SelectInput, SimpleForm, TextInput} from 'react-admin';
import {Grid} from "@mui/material";

export const EducationCreate = () => {
  const choiceMonths = [
    {id: 1, name: 'January'},
    {id: 2, name: 'February'},
    {id: 3, name: 'March'},
    {id: 4, name: 'April'},
    {id: 5, name: 'May'},
    {id: 6, name: 'June'},
    {id: 7, name: 'July'},
    {id: 8, name: 'August'},
    {id: 9, name: 'September'},
    {id: 10, name: 'October'},
    {id: 11, name: 'November'},
    {id: 12, name: 'December'},
  ];

  let choiceYears = [];

  for (let i = new Date().getFullYear(); i > 2000; i--) {
    choiceYears.push({id: i, name: i});
  }

  return (
    <Create>
      <SimpleForm>
        <TextInput autoFocus source="institution" fullWidth validate={required()}/>
        <TextInput source="faculty" fullWidth validate={required()}/>
        <TextInput source="specialization" fullWidth validate={required()}/>

        <Grid item xs={12} sm={4} columnSpacing={2}>
          <SelectInput source="from.month" choices={choiceMonths}/>
          <SelectInput source="from.year" choices={choiceYears}/>
        </Grid>

        <Grid item xs={12} sm={4} columnSpacing={2}>
          <SelectInput source="to.month" choices={choiceMonths}/>
          <SelectInput source="to.year" choices={choiceYears}/>
        </Grid>
      </SimpleForm>
    </Create>
  );
}
