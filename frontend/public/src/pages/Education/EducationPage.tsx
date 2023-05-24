import { useEffect, useState, useContext } from 'react';
import { Chrono } from 'react-chrono';
import { Container } from 'react-bootstrap';
import { Fade } from 'react-awesome-reveal';
import { ThemeContext } from 'styled-components';
import '../../css/education.css';
import FallbackSpinner from "../../components/FallbackSpinner";
import ApiService, {PaginatedCollection, Education} from "../../services/ApiService";
import Header from "../../components/Header";
import {TimelineItemModel} from "react-chrono/dist/models/TimelineItemModel";

interface EducationProps {
  header: string,
}

const EducationPage = ({ header }: EducationProps) => {
  const theme = useContext(ThemeContext);
  const [data, setData] = useState<TimelineItemModel[]|null>(null);

  const loadData = () => {
    ApiService.getEducations()
      .then((response: PaginatedCollection<Education>) => {
        let items = response.data.map<TimelineItemModel>((item: Education) => {
          return {
            title: `${item.from.month}/${item.from.year} - ${item.to ? item.to.month + '/' + item.to.year : 'Until Now'}`,
            cardTitle: item.institution,
            cardSubtitle: item.faculty,
            cardDetailedText: item.specialization,
          }
        })
        setData(items);
      });
  };

  useEffect(() => {
    loadData();
  }, []);

  return (
    <>
      <Header title={header} />
      {data ? (
        <Fade>
          <div className="section-content-container">
            <Container>
              <Chrono
                hideControls
                allowDynamicUpdate
                useReadMore={false}
                items={data}
                cardHeight={250}
                mode='VERTICAL_ALTERNATING'
                theme={{
                  primary: theme.accentColor,
                  secondary: theme.accentColor,
                  cardBgColor: theme.chronoTheme.cardBgColor,
                  titleColor: theme.chronoTheme.titleColor,
                  titleColorActive: theme.chronoTheme.titleColorActive,
                  detailsColor: theme.chronoTheme.titleColor,
                }}
              >
              </Chrono>
            </Container>
          </div>
        </Fade>
      ) : <FallbackSpinner /> }
    </>
  );
}

export default EducationPage;
